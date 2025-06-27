import Form from '@/components/Form';
import { useAuth } from '@/context/AuthContext';
import { api } from '@/helper';
import { useNavigate } from '@tanstack/react-router';

export default function RegisterForm() {
    const navigate = useNavigate();
    const {login} = useAuth();
    return (
        <Form
            title="S'inscrire"
            description={
                (
                    <>
                        Ah ! Tu veux créer un compte ? Très<br />
                        bien, alors j'aurais besoin de ton pseudo<br />
                        et de ton mot de passe.
                    </>
                )
            }
            inputs={[
                {
                    name: "username",
                    label: "Nom d'utilisateur",
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
                    username: formState.username.value,
                    password: formState.password.value
                };
                api.post("/api/register", data).then((res: any) => {
                    if(res.error) {
                        console.error(`Une erreur ${res.code} s'est produite: ${res.error}`);
                        setFormState({...formState, username: {value: data.username, errors: [res.error]}});
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