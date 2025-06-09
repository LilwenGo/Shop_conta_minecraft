import Card from '@/components/Card';
import Form from '@/components/Form';
import { createLazyFileRoute, Link } from '@tanstack/react-router';

export const Route = createLazyFileRoute('/login')({
  component: RouteComponent,
});

function RouteComponent() {
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
            action="/api/login"
        />
        <Link to="/register" className="link small">Je n'ai pas de compte</Link>
    </Card>
  );
}
