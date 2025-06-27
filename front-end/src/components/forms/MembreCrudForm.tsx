import Form from '@/components/Form';
import { api } from '@/helper';
import { useAuth } from '@/context/AuthContext';
import Button from '../Button';
import type { RefObject } from 'react';
import { useQuery, useQueryClient } from '@tanstack/react-query';

export default function MembreCrudForm({dialogRef, currentMembre, setCurrentMembre, membres, setMembres}: {dialogRef: RefObject<HTMLDialogElement | null>, currentMembre?: any, setCurrentMembre: CallableFunction, membres: Array<any>, setMembres: CallableFunction}) {
    const { hasRole } = useAuth();
    const queryClient = useQueryClient();
    const { data, isLoading, error } = useQuery({
        queryKey: ['orphans'], 
        queryFn: () => {
            return api.get('/api/membres/orphans');
        }
    });
    if(isLoading) return (<h2 className="subtitle">Chargement...</h2>);
    if(error) return (<h2 className="subtitle error">Une erreur est survenue</h2>);
    const formInputs = [
        !currentMembre ? {
            type: 'select',
            name: 'membre',
            label: 'Membre',
            options: data?.orphans ? 
                data.orphans.map((o: any) => {
                    return {
                        name: o.name,
                        value: o.id
                    };
                }) : [],
            rules: [{ regex: /^.+$/, message: 'Ce champ est requis' }]
        } :
        (hasRole('Responsable') && {
            type: 'select',
            name: 'role',
            label: 'Rôle*',
            initialValue: currentMembre?.roles.includes('Responsable') ? 'Responsable' : currentMembre?.roles.includes('Moderateur') ? 'Moderateur' : 'Membre',
            options: [{ name: 'Membre' }, { name: 'Moderateur' }],
            rules: [{ regex: /^.+$/, message: 'Ce champ est requis' }],
        })
    ];
    return (
        <Form
            title={currentMembre ? "Modifier le membre" : "Nouveau membre"}
            inputs={formInputs}
            callBack={async (formState: any, setFormState: CallableFunction) => {
                const data = {
                    membreId: formState.membre?.value,
                    role: formState.role?.value ?? 'Membre',
                };
                if(!currentMembre) {
                    const res = await api.post('/api/teams/hire', data);
                    if (res.error) {
                        console.error(`Une erreur ${res.code} s'est produite: ${res.error}`);
                        setFormState({...formState, name: {value: data.membreId, errors: [res.error]}});
                    } else {
                        setMembres([...membres, res.membre]);
                        queryClient.refetchQueries({queryKey: ['orphans']});
                        dialogRef.current?.close();
                    } 
                } else {
                    const res = await api.put(`/api/membres/${currentMembre.id}`, data);
                    if (res.error) {
                        console.error(`Une erreur ${res.code} s'est produite: ${res.error}`);
                        setFormState({...formState, role: {value: data.role, errors: [res.error]}});
                    } else {
                        setMembres(
                            membres.map((m: {id: string, name: string, roles: string[]}) => (m.id === currentMembre.id ? res.membre : m))
                        );
                        dialogRef.current?.close();
                    }
                    setCurrentMembre(null);
                }
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
                        const res = await api.put(`/api/teams/fire`, {membreId: currentMembre.id});
                        if (res.error) {
                            alert(`Erreur ${res.code}: ${res.error}`);
                        } else {
                            queryClient.refetchQueries({queryKey: ['orphans']});
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