import Card from '@/components/Card';
import Form from '@/components/Form';
import { useAuth } from '@/context/AuthContext';
import { api } from '@/helper';
import { createLazyFileRoute, Link, useNavigate } from '@tanstack/react-router';

export const Route = createLazyFileRoute('/register')({
  component: RouteComponent,
});

function RouteComponent() {
  const navigate = useNavigate();
  const {login, hasRole} = useAuth();
  if(hasRole("Membre")) navigate({to: "/team"});
  return (
    <Card>
        <Form
            title="Créer une équipe"
            description={
                (
                    <>
                        Ah ! Tu veux créer une équipe ? Très<br />
                        bien, alors j'aurais besoin du nom de<br />
                        ton équipe, de ton pseudo et de ton <br />
                        mot de passe.
                    </>
                )
            }
            inputs={[
                {
                    name: "team",
                    label: "Nom de l'équipe*",
                    type: "text",
                    rules: [
                        {
                            regex: /^.+$/,
                            message: "Ce champ est requis !"
                        }
                    ]
                },
                {
                    name: "username",
                    label: "Nom du responsable*",
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
            callBack={(formState: any) => {
                const data = {
                    team: formState.team.value,
                    username: formState.username.value,
                    password: formState.password.value
                };
                api.post("/api/register", data).then((res: any) => {
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
        <Link to="/login" className="link small">J'ai déjà un compte</Link>
    </Card>
  );
}
