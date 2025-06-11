<?php 
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\PersistableEntity;

abstract class AbstractService {
    protected EntityManagerInterface $em;

    protected $repository;

    public function __construct(EntityManagerInterface $em, string $entityClass) {
        $this->em = $em;
        $this->repository = $this->em->getRepository($entityClass);
    }

    public function getAll(): array {
        return $this->repository->findAll();
    }

    public function save(PersistableEntity $entity): void {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function delete(PersistableEntity $entity): void {
        $this->em->remove($entity);
        $this->em->flush();
    }
}