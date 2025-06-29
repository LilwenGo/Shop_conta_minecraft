import Button from '@/components/Button';
import Card from '@/components/Card';
import { useAuth } from '@/context/AuthContext';
import { createFileRoute, Link } from '@tanstack/react-router';

export const Route = createFileRoute('/')({
  component: Home,
});

function Home() {
  const { isLogued } = useAuth();
  return (
    <Card>
      <h2 className="subtitle">Bienvenue !</h2>
      {
        !isLogued() ?
        <>
          <p className="paragraph">
            Nous sommes ravis de te voir ! Peux-tu me <br />
            dire de quelle équipe tu es membre ? Sauf <br />
            si tu n'as pas encore de compte ?
          </p>
          <Button to="/login">Je me connecte</Button>
          <Button to="/register" variant="accent">Je crée un compte</Button>
        </> :
        <>
          <p className="paragraph">
            Nous sommes ravis de te voir ! <br />
            De quoi as tu besoin aujourd'hui ?
          </p>
          <Button to="/team">Voir mon équipe</Button>
          <Button to="/transactions" variant="accent">Voir les transactions</Button>
        </>
      }
      <Link to="/about" className="link small">Comment ça marche ?</Link>
    </Card>
  );
}
