<?php 
namespace App\Service;

use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;

class RoleService extends AbstractService {

    public function __construct(EntityManagerInterface $em) {
        parent::__construct($em, Role::class);
    }

    public function getById(int $id): ?Role {
        return $this->repository->findById($id);
    }

    public function getByLibelle(string $libelle): ?Role {
        return $this->repository->findByLibelle($libelle);
    }
}