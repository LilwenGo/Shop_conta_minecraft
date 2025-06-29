import type { RefObject } from "react";
import Button from "../Button";
import Form from "../Form";
import { useQuery } from "@tanstack/react-query";
import { api } from "@/helper";
import { useAuth } from "@/context/AuthContext";

export default function TransactionCrudForm({dialogRef, currentTransaction, setCurrentTransaction, transactions, setTransactions}: {dialogRef: RefObject<HTMLDialogElement | null>, currentTransaction?: any, setCurrentTransaction: CallableFunction, transactions: Array<any>, setTransactions: CallableFunction}) {
    const { user } = useAuth();
    const { data, isLoading, error } = useQuery({
        queryKey: ['items'], 
        queryFn: () => {
            return api.get('/api/items/');
        }
    });
    if(isLoading) return (<h2 className="subtitle">Chargement...</h2>);
    if(error) return (<h2 className="subtitle error">Une erreur est survenue</h2>);
    const formInputs = [
        !currentTransaction && {
            type: 'select',
            name: 'item',
            label: 'Item*',
            options: data?.items ? 
                data.items.map((i: any) => {
                    return {
                        name: i.name,
                        value: i.id
                    };
                }) : [],
            rules: [{ regex: /^.+$/, message: 'Ce champ est requis' }]
        },
        !currentTransaction && {
            type: 'number',
            name: 'qty',
            label: "Quantité mise en vente*",
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
        (currentTransaction && currentTransaction?.membre === user?.name) && {
            type: 'number',
            name: 'refundedSum',
            label: "Some remboursée*",
            initialValue: currentTransaction?.refundedSum ?? '',
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
        }
    ];
    return (
        <Form
            title={currentTransaction ? "Modifier la transaction" : "Nouvelle transaction"}
            inputs={formInputs}
            callBack={async (formState: any, setFormState: CallableFunction) => {
                const data = {
                    item: formState.item?.value,
                    qty: formState.qty?.value,
                    refundedSum: formState.refundedSum?.value,
                };
                if(!currentTransaction) {
                    const res = await api.post('/api/transactions/', data);
                    if (res.error) {
                        console.error(`Une erreur ${res.code} s'est produite: ${res.error}`);
                        setFormState({...formState, item: {value: data.item, errors: [res.error]}});
                    } else {
                        setTransactions([...transactions, res.transaction]);
                        dialogRef.current?.close();
                    } 
                } else {
                    const res = await api.put(`/api/transactions/${currentTransaction.item.id}`, data);
                    if (res.error) {
                        console.error(`Une erreur ${res.code} s'est produite: ${res.error}`);
                        setFormState({...formState, item: {value: data.item, errors: [res.error]}});
                    } else {
                        setTransactions(
                            transactions.map((i: {item: {id: number, name: string, price: number, manager: string}, sum: number, refundedSum: number, membre: string}) => (i.item === currentTransaction.item && i.membre === currentTransaction.membre ? res.transaction : i))
                        );
                        setCurrentTransaction(null);
                        dialogRef.current?.close();
                    }
                }
            }}
        >
            <Button onClick={(e: React.MouseEvent) => {
                e.preventDefault();
                dialogRef.current?.close();
            }} variant='accent'>Annuler</Button>
            {(currentTransaction && user?.name === currentTransaction?.item?.manager) && 
                <Button onClick={async (e: React.MouseEvent) => {
                    e.preventDefault();
                    if(currentTransaction) {
                        const res = await api.del(`/api/transactions/${currentTransaction.item.id}`);
                        if (res.error) {
                            alert(`Erreur ${res.code}: ${res.error}`);
                        } else {
                            setTransactions(
                                transactions.filter((i: {item: {id: number, name: string, price: number, manager: string}, sum: number, refundedSum: number, membre: string}) => (i.item !== currentTransaction.item || i.membre !== currentTransaction.membre))
                            );
                        }
                    }
                    // @ts-ignore
                    dialogRef?.current?.close()
                }} variant='danger'><img src='/images/trash.svg' alt="Supprimer la transaction" className="icon" /></Button>
            }
        </Form>
    );
}