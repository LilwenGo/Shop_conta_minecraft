<?php
namespace App\Repository;

use App\Entity\Team;
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
    public function findByTeam(Team $team): array {
        return $this->createQueryBuilder('t')
            ->addSelect('i', 'm')
            ->join('t.membre', 'm')
            ->join('t.item', 'i')
            ->where('m.team = :team')
            ->setParameter('team', $team)
            ->getQuery()
            ->getResult();
    }
    
    /**
     * @return Transaction[]
     */
    public function findById(string $membreId, int $itemId): ?Transaction {
        return $this->createQueryBuilder('t')
            ->where('t.item = :itemId')
            ->andWhere('t.membre = :membreId')
            ->setParameter('itemId', $itemId)
            ->setParameter('membreId', $membreId)
            ->getQuery()
            ->getOneOrNullResult();
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