import Bubble from '@/components/Bubble';
import Button from '@/components/Button';
import Card from '@/components/Card';
import Dialog from '@/components/Dialog';
import TransactionCrudForm from '@/components/forms/TransactionCrudForm';
import { useAuth } from '@/context/AuthContext';
import { api } from '@/helper';
import { useQuery } from '@tanstack/react-query';
import { createLazyFileRoute, useNavigate } from '@tanstack/react-router';
import { useEffect, useRef, useState } from 'react';

export const Route = createLazyFileRoute('/transactions')({
    component: RouteComponent,
});

function RouteComponent() {
    const navigate = useNavigate();
    const {isLogued, hasRole, user} = useAuth();
    if(!isLogued()) navigate({to: "/login"});
    if(!hasRole(['Responsable', 'Moderateur', 'Membre'])) navigate({to: "/team"});
    const { data, isLoading, error } = useQuery({
        queryKey: ['transactions'], 
        queryFn: () => {
            return api.get('/api/transactions/');
        }
    });
    const dialogRef = useRef<HTMLDialogElement>(null);
    const [transactions, setTransactions] = useState(data?.transactions);
    useEffect(() => {
        if (data?.transactions) {
            setTransactions(data?.transactions);
        }
    }, [data]);
    const [currentTransaction, setCurrentTransaction] = useState<{item: {id: number, name: string, price: number, manager: string}, sum: number, refundedSum: number, membre: string} | null>(null);
    if(isLoading) return (<Card><h2 className="subtitle">Chargement...</h2></Card>);
    if(error) return (<Card><h2 className="subtitle error">Une erreur est survenue</h2></Card>);
    const openDialog = (transaction: {item: {id: number, name: string, price: number, manager: string}, sum: number, refundedSum: number, membre: string} | null) => {
        setCurrentTransaction(transaction);
        dialogRef.current?.showModal();
    }
    return (
      <>
        {!transactions || transactions?.length === 0 ?
            <Card>
                <h2 className="subtitle">Aucune transaction</h2>
                <p className="paragraph">
                    J'ai comme l'impression que ton équipe n'a aucune transaction en cours pour l'instant.
                </p>
            </Card>
        : transactions.map((transaction: any, i: number) => {
            return <Card onClick={(transaction.item.manager === user?.name  || transaction.membre === user?.name) ? () => {openDialog(transaction)} : () => {}} key={`transaction-${i}`} >
                <h2 className="subtitle">{transaction.item.name}</h2>
                <Bubble>{transaction.membre}</Bubble>
                <p className="paragraph">Montant total : {transaction.sum}</p>
                <p className="paragraph">Montant remboursé : {transaction.refundedSum}</p>
                <p className="strong">Reste à rembourser : {transaction.sum - transaction.refundedSum}</p>
            </Card>
        })}
        <Card>
            <h2 className='subtitle'>Ajouter une transaction</h2>
            <Button onClick={() => {openDialog(null)}} variant="accent"><img className='icon' src="/images/plus.svg" alt="Ajouter un transaction" /></Button>
        </Card>
        <Dialog dialogRef={dialogRef}>
            <TransactionCrudForm
                dialogRef={dialogRef}
                currentTransaction={currentTransaction}
                setCurrentTransaction={setCurrentTransaction}
                transactions={transactions}
                setTransactions={setTransactions}
            />
        </Dialog>
      </>
    );
}