import { Outlet, createRootRoute } from '@tanstack/react-router';
import NavLink from '../components/NavLink';
import Header from '../components/Header';
import { useAuth } from '@/context/AuthContext';

export const Route = createRootRoute({
  component: () => {
    const { isLogued, hasRole } = useAuth();

    return (
      <>
        <Header />
        <main>
          <nav id="navigation" aria-label="Menu de navigation" className="show-if-desktop">
            <NavLink to="/">Accueil</NavLink>
            {isLogued() && (
              <NavLink to="/team">Équipe</NavLink>
            )}
            {hasRole(["Responsable","Moderateur","Membre"]) && (
              <>
                <NavLink to="/items">Items</NavLink>
                <NavLink to="/transactions">Transactions</NavLink>
              </>
            )}
          </nav>
          <section id="content">
            <div className="scroll-wrapper">
              <Outlet />
            </div>
          </section>
        </main>
      </>
    );
},
});
