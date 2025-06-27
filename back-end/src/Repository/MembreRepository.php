<?php
namespace App\Repository;

use App\Entity\Membre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MembreRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Membre::class);
    }

    public function findOrphans(): array {
        return $this->createQueryBuilder('m')
            ->addSelect('r')
            ->leftJoin('m.roles', 'r')
            ->andWhere('m.team IS NULL')
            ->getQuery()
            ->getResult();
    }
    
    public function findById(string $id): ?Membre {
        return $this->createQueryBuilder('m')
            ->addSelect('r')
            ->leftJoin('m.roles', 'r')
            ->andWhere('m.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByName(string $name): ?Membre {
        return $this->createQueryBuilder('m')
            ->addSelect('r')
            ->leftJoin('m.roles', 'r')
            ->andWhere('m.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
}