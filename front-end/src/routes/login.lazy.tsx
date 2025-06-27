import Card from '@/components/Card';
import LoginForm from '@/components/forms/LoginForm';
import { useAuth } from '@/context/AuthContext';
import { createLazyFileRoute, Link, useNavigate } from '@tanstack/react-router';

export const Route = createLazyFileRoute('/login')({
  component: RouteComponent,
});

function RouteComponent() {
  const navigate = useNavigate();
  const {hasRole} = useAuth();
  if(hasRole("Membre")) navigate({to: "/team"});
  return (
    <Card>
        <LoginForm/>
        <Link to="/register" className="link small">Je n'ai pas de compte</Link>
    </Card>
  );
}
