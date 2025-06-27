import Form from '@/components/Form';
import { api } from '@/helper';
import { useAuth } from '@/context/AuthContext';
import Button from '../Button';
import type { RefObject } from 'react';

export default function TestCrudForm({dialogRef, currentMembre, setCurrentMembre, membres, setMembres}: {dialogRef: RefObject<HTMLDialogElement | null>, currentMembre?: any, setCurrentMembre: CallableFunction, membres: Array<any>, setMembres: CallableFunction}) {
    const { hasRole } = useAuth();
    const formInputs = [
        {
            type: 'select',
            name: 'role',
            label: 'Rôle*',
            initialValue: currentMembre?.roles.includes('Responsable') ? 'Responsable' : currentMembre?.roles.includes('Moderateur') ? 'Moderateur' : 'Membre',
            hidden: !hasRole('Responsable'),
            options: [{ name: 'Membre' }, { name: 'Moderateur' }],
            rules: [{ regex: /^.+$/, message: 'Ce champ est requis' }],
        },
        !currentMembre && {
            type: 'text',
            name: 'name',
            label: 'Nom du membre*',
            initialValue: currentMembre?.name,
            rules: [{ regex: /^.+$/, message: 'Ce champ est requis' }],
        },
        !currentMembre && {
            type: 'password',
            name: 'password',
            label: 'Mot de passe*',
            rules: [{ regex: /^.+$/, message: 'Ce champ est requis' }],
        },
    ];
    return (
        <Form
            title={currentMembre ? "Modifier le membre" : "Nouveau membre"}
            inputs={formInputs}
            callBack={async (formState: any) => {
                const data = {
                    name: formState.name?.value,
                    password: formState.password?.value,
                    role: formState.role?.value ?? 'Membre',
                };
                if(!currentMembre) {
                    console.log(data);
                } else {
                    console.log(data);
                    setCurrentMembre(null);
                }
                dialogRef.current?.close();
            }}
        >
            <Button onClick={(e: React.MouseEvent) => {
                e.preventDefault();
                dialogRef.current?.close();
            }} variant='accent'>Annuler</Button>
            {currentMembre && 
                <Button onClick={async (e: React.MouseEvent) => {
                    e.preventDefault();
                    if(currentMembre) {
                        const res = await api.del(`/api/membres/${currentMembre.id}`);
                        if (res.error) {
                            alert(`Erreur ${res.code}: ${res.error}`);
                        } else {
                            setMembres(
                                membres.filter((m: {id: string, name: string, roles: string[]}) => (m.id !== currentMembre.id))
                            );
                        }
                    }
                    // @ts-ignore
                    dialogRef?.current?.close()
                }} variant='danger'><img src='/images/trash.svg' alt="Supprimer le membre" className="icon" /></Button>
            }
        </Form>
    );
}