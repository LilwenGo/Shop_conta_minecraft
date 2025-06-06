<?php
namespace App\Repository;

use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RoleRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Role::class);
    }
    
    public function findById(int $id): ?Role {
        return $this->createQueryBuilder('r')
            ->andWhere('i.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByLibelle(string $libelle): ?Role {
        return $this->findOneBy(['libelle' => $libelle]);
    }
}