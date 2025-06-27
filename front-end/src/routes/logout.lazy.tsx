import { useAuth } from '@/context/AuthContext';
import { useQueryClient } from '@tanstack/react-query';
import { createLazyFileRoute, useNavigate } from '@tanstack/react-router'

export const Route = createLazyFileRoute('/logout')({
  component: RouteComponent,
})

function RouteComponent() {
  const {logout} = useAuth();
  const queryClient = useQueryClient();
  const navigate = useNavigate();
  queryClient.clear();
  logout();
  navigate({to: '/'});
}
