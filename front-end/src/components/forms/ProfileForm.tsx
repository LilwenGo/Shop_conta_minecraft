import Form from '@/components/Form';
import { useAuth } from '@/context/AuthContext';
import { api } from '@/helper';
import { useQueryClient } from '@tanstack/react-query';
import type { RefObject } from 'react';

export default function ProfileForm({data, dialogRef}: {data: any, dialogRef: RefObject<HTMLDialogElement | null>}) {
    const {login} = useAuth();
    const queryClient = useQueryClient();
    return (
        <Form
            title="Modifier le profil"
            description={
                (
                    <>
                        Ok, donc dis moi ce que je dois changer.<br />
                        Pour toute modification j'aurais besoin<br />
                        de ton mot de passe actuel.<br />
                        Si tu ne veux pas changer de mot de passe,<br />
                        laisse le champ vide.
                    </>
                )
            }
            inputs={[
                {
                    name: "username",
                    label: "Nom d'utilisateur*",
                    type: "text",
                    initialValue: data.name,
                    rules: [
                        {
                            regex: /^.+$/,
                            message: "Ce champ est requis !"
                        }
                    ]
                },
                {
                    name: "password",
                    label: "Mot de passe actuel*",
                    type: "password",
                    rules: [
                        {
                            regex: /^.+$/,
                            message: "Ce champ est requis !"
                        }
                    ]
                },
                {
                    name: "newPassword",
                    label: "Nouveau mot de passe (facultatif)",
                    type: "password",
                    rules: []
                }
            ]}
            callBack={async (formState: any, setFormState: CallableFunction) => {
                const data = {
                    name: formState.username.value,
                    password: formState.password.value,
                    newPassword: formState.newPassword.value
                };
                let res = await api.put("/api/profile", data);
                if(res.error) {
                    console.error(`Une erreur ${res.code} s'est produite: ${res.error}`);
                    setFormState({...formState, username: {value: data.name, errors: [res.error]}});
                    return;
                } else {
                    await queryClient.clear();
                    login(res.user);
                    window.location.reload();
                    dialogRef.current?.close();
                }
            }}
        />
    );
}