import type { RefObject } from "react";
import Form from "../Form";
import { api } from "@/helper";
import { useQueryClient } from "@tanstack/react-query";
import { useAuth } from "@/context/AuthContext";

export default function TeamForm({dialogRef}: {dialogRef: RefObject<HTMLDialogElement | null>}) {
    const {login} = useAuth();
    const queryClient = useQueryClient();
    return (
        <Form
            title="Créer une équipe"
            description="Dans ce cas j'ai besoin de son nom."
            inputs={[
                {
                    type: 'text',
                    name: 'team',
                    label: 'Nom de l\'équipe*',
                    rules: [
                        {
                            regex: /^.+$/,
                            message: "Ce champ est requis !"
                        }
                    ]
                }
            ]}
            callBack={async (formState: any, setFormState: CallableFunction) => {
                const data = {
                    team: formState.team.value
                };
                api.post("/api/teams/", data).then(async (res: any) => {
                    if(res.error) {
                        console.error(`Une erreur ${res.code} s'est produite: ${res.error}`);
                        setFormState({...formState, team: {value: data.team, errors: [res.error]}});
                        return;
                    } else {
                        login(res.user);
                        await queryClient.refetchQueries({queryKey: ['team']});
                        dialogRef?.current?.close();
                    }
                });
            }}
        />
    );
}