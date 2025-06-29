import Button from '@/components/Button';
import Card from '@/components/Card';
import Dialog from '@/components/Dialog';
import ItemCrudForm from '@/components/forms/ItemCrudForm';
import { useAuth } from '@/context/AuthContext';
import { api } from '@/helper';
import { useQuery } from '@tanstack/react-query';
import { createLazyFileRoute, useNavigate } from '@tanstack/react-router';
import { useEffect, useRef, useState } from 'react';

export const Route = createLazyFileRoute('/items')({
    component: RouteComponent,
});

function RouteComponent() {
    const navigate = useNavigate();
    const {isLogued, isModerator, hasRole} = useAuth();
    if(!isLogued()) navigate({to: "/login"});
    if(!hasRole(['Responsable', 'Moderateur', 'Membre'])) navigate({to: "/team"});
    const { data, isLoading, error } = useQuery({
        queryKey: ['items'], 
        queryFn: () => {
            return api.get('/api/items/');
        }
    });
    const dialogRef = useRef<HTMLDialogElement>(null);
    const [items, setItems] = useState(data?.items);
    useEffect(() => {
        if (data?.items) {
            setItems(data?.items);
        }
    }, [data]);
    const [currentItem, setCurrentItem] = useState<{id: number, name: string, price: number, manager: string} | null>(null);
    if(isLoading) return (<Card><h2 className="subtitle">Chargement...</h2></Card>);
    if(error) return (<Card><h2 className="subtitle error">Une erreur est survenue</h2></Card>);
    const openDialog = (item: {id: number, name: string, price: number, manager: string} | null) => {
        setCurrentItem(item);
        dialogRef.current?.showModal();
    }
    return (
      <>
        {!items || items?.length === 0 ?
            <Card>
                <h2 className="subtitle">Aucun item</h2>
                <p className="paragraph">
                    J'ai comme l'impression que ton équipe n'a aucun item en vente pour l'instant.
                </p>
            </Card>
        : items.map((item: any, i: number) => {
            return <Card onClick={isModerator() ? () => {openDialog(item)} : () => {}} key={`item-${i}`} >
                <h2 className="subtitle">{item.name}</h2>
                <p className="strong">Responsable de vente : {item.manager}</p>
                <p className="paragraph">Prix : {item.price}</p>
            </Card>
        })}
        {isModerator() && <>
            <Card>
                <h2 className='subtitle'>Ajouter un item</h2>
                <Button onClick={() => {openDialog(null)}} variant="accent"><img className='icon' src="/images/plus.svg" alt="Ajouter un item" /></Button>
            </Card>
            <Dialog dialogRef={dialogRef}>
                <ItemCrudForm
                    dialogRef={dialogRef}
                    currentItem={currentItem}
                    setCurrentItem={setCurrentItem}
                    items={items}
                    setItems={setItems}
                />
            </Dialog>
        </>}
      </>
    );
}
