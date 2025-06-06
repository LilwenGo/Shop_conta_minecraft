<?php 
namespace App\Service;

use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;

class TeamService extends AbstractService {

    public function __construct(EntityManagerInterface $em) {
        parent::__construct($em, Team::class);
    }

    public function getById(int $id): ?Team {
        return $this->repository->findById($id);
    }

    public function getByName(string $name): ?Team {
        return $this->repository->findByName($name);
    }
}