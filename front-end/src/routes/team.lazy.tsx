import Bubble from '@/components/Bubble';
import Button from '@/components/Button';
import Card from '@/components/Card';
import Dialog from '@/components/Dialog';
import MembreCrudForm from '@/components/forms/MembreCrudForm';
import TeamForm from '@/components/forms/TeamForm';
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
  const {isLogued, isModerator, getUserId} = useAuth();
  if(!isLogued()) navigate({to: "/login"});
  const { data, isLoading, error } = useQuery({
    queryKey: ['team'], 
    queryFn: () => {
      return api.get('/api/teams/my_team');
    }
  });
  const dialogRef = useRef<HTMLDialogElement>(null);
  const team = data?.team;
  const [membres, setMembres] = useState(team?.membres);
  useEffect(() => {
    if (team?.membres && (!membres || membres?.length === 0)) {
      setMembres(team.membres);
    }
  }, [team]);
  const [currentMembre, setCurrentMembre] = useState<{id: string, name: string, roles: string[]} | null>(null);
  if(isLoading) return (<Card><h2 className="subtitle">Chargement...</h2></Card>);
  if(error) return (<Card><h2 className="subtitle error">Une erreur est survenue</h2></Card>);
  if(!team) {
    return (
      <>
        <Card>
          <h2 className="subtitle">Aucune équipe</h2>
          <p className="paragraph">
            Salut ! Tu n'as pas encore d'équipe à ce que je vois.<br/>
            Tu veux en créer une ?
          </p>
          <Button onClick={() => {dialogRef.current?.showModal()}}>Créer une équipe</Button>
        </Card>
        <Dialog dialogRef={dialogRef}>
          <TeamForm dialogRef={dialogRef} />
        </Dialog>
      </>
    );
  }
  const openDialog = (membre: {id: string, name: string, roles: string[]} | null) => {
    if(membre && membre.id === getUserId()) navigate({to: '/profile'});
    setCurrentMembre(membre);
    dialogRef.current?.showModal();
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
              return <Bubble onClick={() => {openDialog(m)}} key={`membre-${m.id}`}>{`${roleToDisplay} ${m.name}`}</Bubble>;
            })
          }
          {isModerator() && <Button onClick={() => {openDialog(null)}} variant="accent"><img className='icon' src="/images/plus.svg" alt="Ajouter un membre" /></Button>}
        </div>
      </Card>
      {
        isModerator() && <Dialog dialogRef={dialogRef}>
        <MembreCrudForm 
          dialogRef={dialogRef} 
          membres={membres} 
          setMembres={setMembres} 
          currentMembre={currentMembre}
          setCurrentMembre={setCurrentMembre}
        />
        </Dialog>
      }
    </>
  );
}