import { Link } from '@tanstack/react-router';
import { useAuth } from '@/context/AuthContext';
import Button from './Button';
import Burger from './Burger';
import NavLink from './NavLink';

export default function Header() {
  const { isLogued, hasRole } = useAuth();

  let authButtons;
  if(isLogued()) {
    authButtons = (
      <>
        <Button to="/logout">Se déconnecter</Button>
        <Button to="/profile" variant="accent">Profil</Button>
      </>
    );
  } else {
    authButtons = (
      <>
        <Button to="/login">Se connecter</Button>
        <Button to="/register" variant="accent">S'inscrire</Button>
      </>
    );
  }

  return (
    <header className="banner">
      <Link to="/" aria-label="Retour à l'accueil">
        <img className="logo" src="/images/logo.png" alt="Logo du site" />
      </Link>
      <h1 className="title">Shop Compta For Minecraft</h1>
      <Burger footElements={authButtons} className="show-if-mobile">
        <div className="burger-section">
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
        </div>
      </Burger>
      <nav id="authentication" aria-label="Authentification" className="show-if-desktop">
        {authButtons}
      </nav>
    </header>
  )
}
