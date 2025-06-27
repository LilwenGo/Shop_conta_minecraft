import { createLazyFileRoute, useNavigate } from '@tanstack/react-router';
import { useAuth } from '@/context/AuthContext';
import Card from '@/components/Card';
import Button from '@/components/Button';
import Dialog from '@/components/Dialog';
import { useRef } from 'react';
import { api } from '@/helper';
import ProfileForm from '@/components/forms/ProfileForm';

export const Route = createLazyFileRoute('/profile')({
  component: RouteComponent,
});

function RouteComponent() {
  const {isLogued, user} = useAuth();
  const navigate = useNavigate();
  const dialogRef = useRef<HTMLDialogElement>(null);
  const confirmDialogRef = useRef<HTMLDialogElement>(null);
  const openDialog = () => {
    dialogRef.current?.showModal();
  }
  if(!user || !isLogued()) return (<Card><h2 className="subtitle error">Aucune donnée, vous n'êtes pas connécté</h2></Card>);
  return (
    <>
      <Card>
        <h2 className="subtitle">Mon profil</h2>
        <p className="paragraph">
          Oui ! De quoi as tu besoin ?<br />
          Tu veux modifier quelque chose ?
        </p>
        <span className='strong'>Nom : {user?.name ?? ''}</span>
        <div className="bubble-group">
        <span className='strong'>
          {user?.roles.reduce((acc: string, r: string, i: number, arr: string[]) => {
            return `${acc} ${r}${i !== arr.length - 1 ? ',' : ''}`;
          }, 'Roles :')}
        </span>
        </div>
        <Button onClick={() => {openDialog()}} variant='accent'>Modifier</Button>
        <Button onClick={() => {confirmDialogRef.current?.showModal()}} variant='danger'>Supprimer le compte</Button>
      </Card>
      <Dialog dialogRef={dialogRef}>
        <ProfileForm data={user} dialogRef={dialogRef} />
      </Dialog>
      <Dialog dialogRef={confirmDialogRef}>
        <h2 className="subtitle">Es-tu sûr ?</h2>
        <p className="paragraph">
          Une minute ! Tu es sûr de toi ?<br />
          Ton compte serait définitivement supprimé !
        </p>
        <div className="bubble-group">
          <Button onClick={(e: React.MouseEvent) => {
            e.preventDefault();
            dialogRef.current?.close();
          }} variant='accent'>Annuler</Button>
          <Button onClick={async (e: React.MouseEvent) => {
            e.preventDefault();
            const res = await api.del(`/api/membres/${user.id}`);
            if (res.error) {
              alert(`Erreur ${res.code}: ${res.error}`);
            } else {
              navigate({to: '/logout'});
            }
          }} variant='danger'>Confirmer</Button>
        </div>
      </Dialog>
    </>
  );
}
