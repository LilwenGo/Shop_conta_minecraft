<?php
namespace Project\Models;

/**Class Category */
class Category {
    //Properties
    private int $id;
    private int $id_team;
    private string $libelle;
    private string $role;
    private array $items;
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
    
    public function getLibelle(): string {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): void {
        $this->libelle = $libelle;
    }
    
    public function getRole(): string {
        return $this->role;
    }

    public function setRole(string $role): void {
        $this->role = $role;
    }

    public function getItems(): array {
        if(!isset($this->items)) {
            $m = new ItemManager();
            $this->items = $m->getFromCategory($this->id);
        }
        return $this->items;
    }

    public function getSolds(): array {
        if(!isset($this->solds)) {
            $m = new SoldManager();
            $this->solds = $m->getFromCategory($this->id);
        }
        return $this->solds;
    }
}