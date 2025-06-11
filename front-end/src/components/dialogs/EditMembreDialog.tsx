import Form from '@/components/Form';
import { useAuth } from '@/context/AuthContext';
import { api } from '@/helper';
import type { Ref } from 'react';
import Button from '../Button';

export default function EditMembreDialog({ dialogRef, membres, setMembres, editingItem }: {dialogRef: Ref<HTMLDialogElement> | undefined, membres: Array<{id: string, name: string, roles: string[]}>, setMembres: CallableFunction, editingItem: {id: string, name: string, roles: string[]} | undefined}) {
  const { hasRole } = useAuth();

  const formInputs = [
    {
      type: 'select',
      name: 'role',
      label: 'Rôle*',
      hidden: !hasRole('Responsable'),
      options: [{ name: 'Membre' }, { name: 'Moderateur' }],
      rules: [{ regex: /^.+$/, message: 'Ce champ est requis' }],
    }
  ];

  return (
    <dialog ref={dialogRef}>
        <div className='card'>
            <Form
                title="Modifier le membre"
                inputs={formInputs}
                callBack={async (formState: any) => {
                const data = {
                    role: formState.role?.value ?? 'Membre',
                };
                if(editingItem) {
                    const res = await api.put(`/api/membres/${editingItem.id}`, data);
                    if (res.error) {
                        alert(`Erreur ${res.code}: ${res.error}`);
                    } else {
                        setMembres(
                            membres.map((m: {id: string, name: string, roles: string[]}) => (m.id === editingItem.id ? res.membre : m))
                        );
                    }
                }
                // @ts-ignore
                dialogRef?.current?.close();
                }}
            >
                {/* @ts-ignore*/}
                <Button onClick={() => dialogRef?.current?.close()} variant='accent'>Annuler</Button>
                <Button onClick={async () => {
                    if(editingItem) {
                        const res = await api.del(`/api/membres/${editingItem.id}`);
                        if (res.error) {
                            alert(`Erreur ${res.code}: ${res.error}`);
                        } else {
                            setMembres(
                                membres.filter((m: {id: string, name: string, roles: string[]}) => (m.id !== editingItem.id))
                            );
                        }
                    }
                    // @ts-ignore
                    dialogRef?.current?.close()
                }} variant='danger'><img src='/images/trash.svg' alt="Supprimer le membre" className="icon" /></Button>
            </Form>
        </div>
    </dialog>
  );
}
