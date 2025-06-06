<?php 
namespace App\Service;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;

class TransactionService extends AbstractService {

    public function __construct(EntityManagerInterface $em) {
        parent::__construct($em, Transaction::class);
    }

    public function getByMembre(int $id): array {
        return $this->repository->findByMembre($id);
    }

    public function getByItem(string $name): array {
        return $this->repository->findByItem($name);
    }
}