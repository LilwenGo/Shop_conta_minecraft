<?php 
namespace App\Entity;

interface PersistableEntity {
    public function toJson(): array;
}