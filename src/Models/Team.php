<?php
namespace Project\Models;

/**Class Team */
class Team {
    //Properties
    private int $id;
    private string $login;
    private string $password;

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
}