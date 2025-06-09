import { Outlet, createRootRoute } from '@tanstack/react-router';
import NavLink from '../components/NavLink';
import Header from '../components/Header';
import { useAuth } from '@/context/AuthContext';

export const Route = createRootRoute({
  component: () => {
    const { hasRole } = useAuth();

    return (
      <>
        <Header />
        <main>
          <nav id="navigation" aria-label="Menu de navigation" className="show-if-desktop">
            <NavLink to="/">Accueil</NavLink>
            {hasRole("Membre") && (
              <>
                <NavLink to="/team">Équipe</NavLink>
                <NavLink to="/items">Items</NavLink>
                <NavLink to="/transactions">Transactions</NavLink>
              </>
            )}
          </nav>
          <section id="content">
            <Outlet />
          </section>
        </main>
      </>
    )
},
})
