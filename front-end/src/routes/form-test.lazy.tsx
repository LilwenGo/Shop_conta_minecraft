import Bubble from '@/components/Bubble';
import Button from '@/components/Button';
import Card from '@/components/Card';
import Dialog from '@/components/Dialog';
import TestCrudForm from '@/components/forms/TestCrudForm';
import { createLazyFileRoute } from '@tanstack/react-router';
import { useRef, useState, useEffect } from 'react';

export const Route = createLazyFileRoute('/form-test')({
  component: RouteComponent,
});

function RouteComponent() {
  const team = {
    name: 'Test Form',
    membres: [
      {
        id: "abcdefg",
        name: "User 1",
        roles: [
          "Membre"
        ]
      }
    ]
  };
  const [membres, setMembres] = useState(team?.membres);
  useEffect(() => {
    if (team?.membres && (!membres || membres?.length === 0)) {
      setMembres(team.membres);
    }
  }, [team]);
  const [currentMembre, setCurrentMembre] = useState<{id: string, name: string, roles: string[]} | null>(null);
  const dialogRef = useRef<HTMLDialogElement>(null);

  const openDialog = (membre: {id: string, name: string, roles: string[]} | null) => {
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
          {true && <Button onClick={() => {openDialog(null)}} variant="accent"><img className='icon' src="/images/plus.svg" alt="Ajouter un membre" /></Button>}
        </div>
      </Card>
      {
        true && <Dialog dialogRef={dialogRef}>
        <TestCrudForm 
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