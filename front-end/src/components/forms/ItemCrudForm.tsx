import Form from '@/components/Form';
import { api } from '@/helper';
import Button from '../Button';
import type { RefObject } from 'react';
import { useQuery } from '@tanstack/react-query';

export default function ItemCrudForm({dialogRef, currentItem, setCurrentItem, items, setItems}: {dialogRef: RefObject<HTMLDialogElement | null>, currentItem?: any, setCurrentItem: CallableFunction, items: Array<any>, setItems: CallableFunction}) {
    const { data, isLoading, error } = useQuery({
        queryKey: ['team'], 
        queryFn: () => {
            return api.get('/api/teams/my_team');
        }
    });
    if(isLoading) return (<h2 className="subtitle">Chargement...</h2>);
    if(error) return (<h2 className="subtitle error">Une erreur est survenue</h2>);
    const formInputs = [
        {
            type: 'text',
            name: 'name',
            label: "Nom de l'item*",
            initialValue: currentItem?.name ?? '',
            rules: [
                {
                    regex: /^.+$/,
                    message: "Ce champ est requis !"
                }
            ]
        },
        {
            type: 'number',
            name: 'price',
            label: "Prix de l'item*",
            initialValue: currentItem?.price ?? '',
            rules: [
                {
                    regex: /^.+$/,
                    message: "Ce champ est requis !"
                },
                {
                    regex: /^[0-9]+$/,
                    message: "Ce champ doit contenir un nombre entier positif"
                }
            ]
        },
        {
            type: 'select',
            name: 'manager',
            label: 'Responsable de vente*',
            initialValue: currentItem?.manager ?? '',
            options: data?.team?.membres ? 
                data.team.membres.map((m: any) => {
                    return {
                        name: m.name
                    };
                }) : [],
            rules: [{ regex: /^.+$/, message: 'Ce champ est requis' }]
        } 
    ];
    return (
        <Form
            title={currentItem ? "Modifier l'item" : "Nouvel item"}
            inputs={formInputs}
            callBack={async (formState: any, setFormState: CallableFunction) => {
                const data = {
                    name: formState.name?.value,
                    price: formState.price?.value,
                    manager: formState.manager?.value,
                };
                if(!currentItem) {
                    const res = await api.post('/api/items/', data);
                    if (res.error) {
                        console.error(`Une erreur ${res.code} s'est produite: ${res.error}`);
                        setFormState({...formState, name: {value: data.name, errors: [res.error]}});
                    } else {
                        setItems([...items, res.item]);
                        dialogRef.current?.close();
                    } 
                } else {
                    const res = await api.put(`/api/items/${currentItem.id}`, data);
                    if (res.error) {
                        console.error(`Une erreur ${res.code} s'est produite: ${res.error}`);
                        setFormState({...formState, name: {value: data.name, errors: [res.error]}});
                    } else {
                        setItems(
                            items.map((m: {id: number, name: string, price: number, manager: string}) => (m.id === currentItem.id ? res.item : m))
                        );
                        dialogRef.current?.close();
                    }
                    setCurrentItem(null);
                }
            }}
        >
            <Button onClick={(e: React.MouseEvent) => {
                e.preventDefault();
                dialogRef.current?.close();
            }} variant='accent'>Annuler</Button>
            {currentItem && 
                <Button onClick={async (e: React.MouseEvent) => {
                    e.preventDefault();
                    if(currentItem) {
                        const res = await api.del(`/api/items/${currentItem.id}`);
                        if (res.error) {
                            alert(`Erreur ${res.code}: ${res.error}`);
                        } else {
                            setItems(
                                items.filter((i: {id: number, name: string, price: number, manager: string}) => (i.id !== currentItem.id))
                            );
                        }
                    }
                    // @ts-ignore
                    dialogRef?.current?.close()
                }} variant='danger'><img src='/images/trash.svg' alt="Supprimer l'item" className="icon" /></Button>
            }
        </Form>
    );
}