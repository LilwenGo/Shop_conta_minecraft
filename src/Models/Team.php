<?php
namespace Project\Models;

/**Class Team */
class Team {
    //Properties
    private int $id;
    private string $login;
    private string $password;
    private array $membres;
    private array $admins;
    private array $categories;
    private array $items;

    //Accessors
    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }
    
    public function getLogin(): string {
        return $this->login;
    }

    public function setLogin(string $login): void {
        $this->login = $login;
    }
    
    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function getMembres(): array {
        if(!isset($this->membres)) {
            $m = new MembreManager();
            $this->membres = $m->getFromTeam($this->id);
        }
        return $this->membres;
    }
    
    public function getAdmins(): array {
        if(!isset($this->admins)) {
            $m = new AdminManager();
            $this->admins = $m->getFromTeam($this->id);
        }
        return $this->admins;
    }

    public function getCategories(): array {
        if(!isset($this->categories)) {
            $m = new CategoryManager();
            $this->categories = $m->getFromTeam($this->id);
        }
        return $this->categories;
    }

    public function getItems(): array {
        if(!isset($this->items)) {
            $m = new ItemManager();
            $this->items = $m->getFromTeam($this->id);
        }
        return $this->items;
    }
}