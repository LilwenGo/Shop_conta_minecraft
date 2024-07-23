<?php
namespace Project\Models;

/**Class Category */
class Category {
    //Properties
    private int $id;
    private int $id_team;
    private string $libelle;

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
}