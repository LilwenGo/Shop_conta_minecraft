<?php 
namespace App\Service;

use App\Entity\Item;
use App\Entity\Membre;
use Doctrine\ORM\EntityManagerInterface;

class ItemService extends AbstractService {

    public function __construct(EntityManagerInterface $em) {
        parent::__construct($em, Item::class);
    }

    public function getById(int $id): ?Item {
        return $this->repository->findById($id);
    }

    public function getByName(string $name): ?Item {
        return $this->repository->findByName($name);
    }

    public function transferItems(Membre $from, Membre $to): void {
        // Récupérer les items managés par le membre source
        $items = $this->repository->createQueryBuilder('i')
            ->where('i.manager = :from')
            ->setParameter('from', $from)
            ->getQuery()
            ->getResult();

        // Modifier en mémoire
        foreach ($items as $item) {
            $item->setManager($to);
        }

        // Doctrine va détecter les changements (Unit of Work)
        $this->em->flush();
    }
}