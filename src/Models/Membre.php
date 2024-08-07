<?php
namespace Project\Models;

/**Class Membre */
class Membre {
    //Properties
    private int $id;
    private int $id_team;
    private string $name;
    private array $categories;
    private array $solds;

    //Accessors
    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }
    
    public function getId_team(): int {
        return $this->id_team;
    }

    public function setId_team(int $id_team): void {
        $this->id_team = $id_team;
    }
    
    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getCategories(): array {
        if(!isset($this->categories)) {
            $m = new CategoryManager();
            $this->categories = $m->getFromMembre($this->id);
        }
        return $this->categories;
    }

    public function getSolds(): array {
        if(!isset($this->solds)) {
            $m = new SoldManager();
            $this->solds = $m->getFromMembre($this->id);
        }
        return $this->solds;
    }
}