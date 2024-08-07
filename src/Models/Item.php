<?php
namespace Project\Models;

/**
 * Class Item
 */
class Item {
    //Properties
    private int $id;
    private int $id_team;
    private string $category;
    private string $libelle;
    private float $price;
    private int $total_selled;
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
    
    public function getCategory(): string {
        return $this->category;
    }

    public function setCategory(string $category): void {
        $this->category = $category;
    }

    public function getLibelle(): string {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): void {
        $this->libelle = $libelle;
    }
    
    public function getPrice(): float {
        return $this->price;
    }

    public function setPrice(float $price): void {
        $this->price = $price;
    }

    public function getTotal_selled(): int {
        return $this->total_selled;
    }

    public function setTotal_selled(int $total_selled): void {
        $this->total_selled = $total_selled;
    }

    public function getSolds(): array {
        if(!isset($this->solds)) {
            $m = new SoldManager();
            $this->solds = $m->getFromItem($this->id);
        }
        return $this->solds;
    }
}