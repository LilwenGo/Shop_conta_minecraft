<?php 
namespace App\Service;

use App\Entity\Item;
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
}