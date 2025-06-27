import Card from '@/components/Card';
import RegisterForm from '@/components/forms/RegisterForm';
import { useAuth } from '@/context/AuthContext';
import { createLazyFileRoute, Link, useNavigate } from '@tanstack/react-router';

export const Route = createLazyFileRoute('/register')({
  component: RouteComponent,
});

function RouteComponent() {
  const navigate = useNavigate();
  const {hasRole} = useAuth();
  if(hasRole("Membre")) navigate({to: "/team"});
  return (
    <Card>
        <RegisterForm />
        <Link to="/login" className="link small">J'ai déjà un compte</Link>
    </Card>
  );
}
