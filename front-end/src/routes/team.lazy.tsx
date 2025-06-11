import Bubble from '@/components/Bubble';
import Button from '@/components/Button';
import Card from '@/components/Card';
import AddMembreDialog from '@/components/dialogs/AddMembreDialog';
import EditMembreDialog from '@/components/dialogs/EditMembreDialog';
import { useAuth } from '@/context/AuthContext';
import { api } from '@/helper';
import { useQuery } from '@tanstack/react-query';
import { createLazyFileRoute, useNavigate } from '@tanstack/react-router';
import { useRef, useState, useEffect } from 'react';

export const Route = createLazyFileRoute('/team')({
  component: RouteComponent,
});

function RouteComponent() {
  const navigate = useNavigate();
  const {hasRole, isModerator} = useAuth();
  if(!hasRole('Membre')) navigate({to: "/"});
  const { data, isLoading, error } = useQuery({
    queryKey: ['team'], 
    queryFn: () => {
      return api.get('/api/teams/my_team');
    }
  });
  const team = data?.team;
  const [membres, setMembres] = useState(team?.membres);
  useEffect(() => {
    if (team?.membres && (!membres || membres?.length === 0)) {
      setMembres(team.membres);
    }
  }, [team]);
  const [editingItem, setEditingItem] = useState<{id: string, name: string, roles: string[]}>();
  const addDialogRef = useRef<HTMLDialogElement>(null);
  const editDialogRef = useRef<HTMLDialogElement>(null);
  if(isLoading) return (<Card><h2 className="subtitle">Chargement...</h2></Card>);
  if(error) return (<Card><h2 className="subtitle error">Une erreur est survenue</h2></Card>);

  const openEditDialog = (membre: {id: string, name: string, roles: string[]}) => {
    setEditingItem(membre);
    editDialogRef.current?.showModal();
  }

  const openAddDialog = () => {
    addDialogRef.current?.showModal();
  }
  return (
    <>
      <Card>
        <h2 className="subtitle">Équipe {team?.name}</h2>
        <p className="paragraph">
          Hey ! Je suis content que tu ais pu rejoindre une équipe.<br />
          Voici la liste des membres depuis la dernière modification.
        </p>
        <div className="bubble-group">
          {
            membres?.map((m: {id: string, name: string, roles: string[]}) => {
              let roleToDisplay = m.roles.includes('Responsable') ? 'Responsable' : m.roles.includes('Moderateur') ? 'Moderateur' : 'Membre';
              return <Bubble onClick={() => {openEditDialog(m)}} key={`membre-${m.id}`}>{`${roleToDisplay} ${m.name}`}</Bubble>;
            })
          }
          {isModerator() && <Button onClick={() => {openAddDialog()}} variant="accent"><img className='icon' src="/images/plus.svg" alt="Ajouter un membre" /></Button>}
        </div>
      </Card>
      {
        isModerator() && <>
        <AddMembreDialog 
          dialogRef={addDialogRef} 
          membres={membres} 
          setMembres={setMembres} 
        />
        <EditMembreDialog 
          dialogRef={editDialogRef} 
          membres={membres} 
          setMembres={setMembres} 
          editingItem={editingItem} 
        />
        </>
      }
    </>
  );
}