import Card from '@/components/Card';
import Form from '@/components/Form';
import { useAuth } from '@/context/AuthContext';
import { api } from '@/helper';
import { createLazyFileRoute, Link } from '@tanstack/react-router';

export const Route = createLazyFileRoute('/login')({
  component: RouteComponent,
});

function RouteComponent() {
  const {login} = useAuth();
  return (
    <Card>
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
                            message: "Ce champs est requis !"
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
                            message: "Ce champs est requis !"
                        }
                    ]
                }
            ]}
            callBack={(formState: any) => {
                const data = {
                    name: formState.username.value,
                    password: formState.password.value
                };
                api.post("/api/login", data).then((res: any) => {
                    if(res.error) {
                        let message = `Une erreur ${res.code} s'est produite: ${res.error}`;
                        console.error(message);
                        alert(message);
                        return;
                    } else {
                        login(res.user);
                    }
                });
            }}
        />
        <Link to="/register" className="link small">Je n'ai pas de compte</Link>
    </Card>
  );
}
