<?php
namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TransactionRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Transaction::class);
    }
    
    /**
     * @return Transaction[]
     */
    public function findByMembre(int $id): array {
        return $this->createQueryBuilder('t')
            ->andWhere('t.membre = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Transaction[]
     */
    public function findByItem(int $id): array {
        return $this->createQueryBuilder('t')
            ->andWhere('t.item = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
}