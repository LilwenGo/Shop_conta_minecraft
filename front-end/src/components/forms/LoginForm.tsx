import Form from '@/components/Form';
import { useAuth } from '@/context/AuthContext';
import { api } from '@/helper';
import { useNavigate } from '@tanstack/react-router';

export default function LoginForm() {
    const navigate = useNavigate();
    const {login} = useAuth();
    return (
        <Form
            title="Se connecter"
            description={
                (
                    <>
                        Génial ! Tu as déja un compte. Ça me<br />
                        fera moins de paperasse. Dans ce cas<br />
                        j'ai besoin de ton pseudo et de ton<br />
                        mot de passe.
                    </>
                )
            }
            inputs={[
                {
                    name: "username",
                    label: "Nom d'utilisateur*",
                    type: "text",
                    rules: [
                        {
                            regex: /^.+$/,
                            message: "Ce champ est requis !"
                        }
                    ]
                },
                {
                    name: "password",
                    label: "Mot de passe*",
                    type: "password",
                    rules: [
                        {
                            regex: /^.+$/,
                            message: "Ce champ est requis !"
                        }
                    ]
                }
            ]}
            callBack={(formState: any, setFormState: CallableFunction) => {
                const data = {
                    name: formState.username.value,
                    password: formState.password.value
                };
                api.post("/api/login", data).then((res: any) => {
                    if(res.error) {
                        console.error(`Une erreur ${res.code} s'est produite: ${res.error}`);
                        setFormState({...formState, username: {value: data.name, errors: [res.error]}});
                        return;
                    } else {
                        login(res.user);
                        navigate({to: '/team'});
                    }
                });
            }}
        />
    );
}