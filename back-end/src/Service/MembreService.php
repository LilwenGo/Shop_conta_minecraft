<?php 
namespace App\Service;

use App\Entity\Membre;
use Doctrine\ORM\EntityManagerInterface;

class MembreService extends AbstractService {

    public function __construct(EntityManagerInterface $em) {
        parent::__construct($em, Membre::class);
    }

    public function getById(string $id): ?Membre {
        return $this->repository->findById($id);
    }

    public function getByName(string $name): ?Membre {
        return $this->repository->findByName($name);
    }
}