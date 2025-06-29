<?php 
namespace App\Service;

use App\Entity\Team;
use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;

class TransactionService extends AbstractService {

    public function __construct(EntityManagerInterface $em) {
        parent::__construct($em, Transaction::class);
    }

    public function getByTeam(Team $team): array {
        return $this->repository->findByTeam($team);
    }

    public function getById(string $membreId, int $itemId): ?Transaction {
        return $this->repository->findById($membreId, $itemId);
    }

    public function getByMembre(int $id): array {
        return $this->repository->findByMembre($id);
    }

    public function getByItem(string $name): array {
        return $this->repository->findByItem($name);
    }
}