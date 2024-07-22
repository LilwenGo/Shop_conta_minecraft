<?php
namespace Project\Models;

class Admin {
    //Properties
    private int $id;
    private int $id_team;
    private string $login;
    private string $password;

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
}