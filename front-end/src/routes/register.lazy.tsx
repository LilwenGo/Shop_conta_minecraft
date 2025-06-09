import Card from '@/components/Card';
import Form from '@/components/Form';
import { createLazyFileRoute, Link } from '@tanstack/react-router';

export const Route = createLazyFileRoute('/register')({
  component: RouteComponent,
});

function RouteComponent() {
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
                            message: "Ce champs est requis !"
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
            action="/api/register"
        />
        <Link to="/login" className="link small">J'ai déjà un compte</Link>
    </Card>
  );
}
