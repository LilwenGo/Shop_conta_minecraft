import Form from '@/components/Form';
import { useAuth } from '@/context/AuthContext';
import { api } from '@/helper';
import type { Ref } from 'react';
import Button from '../Button';

export default function AddMembreDialog({ dialogRef, membres, setMembres }: {dialogRef: Ref<HTMLDialogElement> | undefined, membres: Array<{id: string, name: string, roles: string[]}>, setMembres: CallableFunction}) {
  const { hasRole } = useAuth();

  const formInputs = [
    {
      type: 'select',
      name: 'role',
      label: 'Rôle*',
      hidden: !hasRole('Responsable'),
      options: [{ name: 'Membre' }, { name: 'Moderateur' }],
      rules: [{ regex: /^.+$/, message: 'Ce champ est requis' }],
    },
    {
      type: 'text',
      name: 'name',
      label: 'Nom du membre*',
      rules: [{ regex: /^.+$/, message: 'Ce champ est requis' }],
    },
    {
      type: 'password',
      name: 'password',
      label: 'Mot de passe*',
      rules: [{ regex: /^.+$/, message: 'Ce champ est requis' }],
    },
  ];

  return (
    <dialog ref={dialogRef}>
        <div className='card'>
            <Form
                title="Nouveau membre"
                inputs={formInputs}
                callBack={async (formState: any) => {
                    const data = {
                        name: formState.name.value,
                        password: formState.password.value,
                        role: formState.role?.value ?? 'Membre',
                    };

                    const res = await api.post('/api/membres', data);
                    if (res.error) {
                        alert(`Erreur ${res.code}: ${res.error}`);
                    } else {
                        setMembres([...membres, res.membre]);
                    }
                    // @ts-ignore
                    dialogRef?.current?.close();
                }}
            >
                {/* @ts-ignore*/}
                <Button onClick={() => dialogRef?.current?.close()} variant='accent'>Annuler</Button>
            </Form>
        </div>
    </dialog>
  );
}