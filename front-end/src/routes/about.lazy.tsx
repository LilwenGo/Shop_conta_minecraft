import Button from '@/components/Button';
import Card from '@/components/Card';
import { createLazyFileRoute } from '@tanstack/react-router'

export const Route = createLazyFileRoute('/about')({
  component: RouteComponent,
})

function RouteComponent() {
  return (
    <Card>
      <h2 className="subtitle">Comment ça marche ?</h2>
      <p className="paragraph">
        Très bien ! Je vais t'expliquer comment fonctionne ce site. Ce site a été conçu et pensé pour les joueurs du serveur Minecraft StoryCraft. Il leur permet de gérer la comptabilité pour les différents objets qu'ils mettent en vente sur le serveur.<br /><br />
        Ainsi, des joueurs d'une même équipe peuvent savoir plus facilement qui est-ce qui a mis tel ou tel objet en vente, et se rembourser entre eux si besoin.<br /><br />
        Pour pouvoir utiliser le site, le responsable d'une équipe doit tout d'abord la créer. Il remplit alors le nom de l'équipe, son pseudo, et son mot de passe. Une fois l'équipe créée, son responsable peut librement : ajouter et supprimer des membres sur la page "Équipe", ajouter et supprimer les types d'objets en vente sur la page "Items" et les modifier.<br /><br />
        Le responsable peut aussi modifier les rôles des membres de son équipe. Ainsi, il peut désigner des "Modérateurs", qui sont des membres disposant des mêmes droits que le responsable, à l'exception du droit de modifier les rôles des membres.<br /><br />
        Enfin, n'importe quel membre d'une équipe peut démarrer, modifier et supprimer une transaction sur la page "Transactions". Une transaction consiste en la mise en vente d'un objet par un joueur. Ensuite, le responsable de la vente de ce type d'objet devra s'occuper de rembourser cette personne. Pour démarrer une transaction, le membre doit sélectionner le type d'objet qu'il souhaite vendre, et la quantité qu'il souhaite vendre.<br /><br />
        Ouf ! Ça y est, je peux enfin souffler. En tout cas profite bien du site, et si tu as une question ou une remarque <a className="link" href="mailto:pro.lilian.orgeta@gmail.com" target="_blank">écris au développeur</a>.
      </p>
      <Button to="/">Retourner à l'accueil</Button>
    </Card>
  );
}
