import Button from '@/components/Button';
import Card from '@/components/Card';
import { createFileRoute, Link } from '@tanstack/react-router';

export const Route = createFileRoute('/')({
  component: Home,
});

function Home() {
  return (
    <Card>
      <h2 className="subtitle">Bienvenue !</h2>
      <p className="paragraph">
        Nous sommes ravis de te voir ! Peux-tu me <br />
        dire de quelle équipe tu es membre ? Sauf <br />
        si tu es là pour en créer une ?
      </p>
      <Button to="/login">Je me connecte</Button>
      <Button to="/register" variant="accent">Je crée une équipe</Button>
      <Link to="/about" className="link small">Comment ça marche ?</Link>
    </Card>
  );
}
