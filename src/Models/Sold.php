<?php
namespace Project\Models;

class Sold {
    private int $id_item;
    private string $item;
    private int $id_membre;
    private string $membre;
    private int $quantity;
    private int $refunded;

    public function getId_item(): int {
        return $this->id_item;
    }
    
    public function setId_item(int $id_item): void {
        $this->id_item = $id_item;
    }
    
    public function getItem(): string {
        return $this->item;
    }
    
    public function setItem(string $item): void {
        $this->item = $item;
    }

    public function getId_membre(): int {
        return $this->id_membre;
    }
    
    public function setId_membre(int $id_membre): void {
        $this->id_membre = $id_membre;
    }
    
    public function getMembre(): string {
        return $this->membre;
    }
    
    public function setMembre(string $membre): void {
        $this->membre = $membre;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }
    
    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
    }

    public function getRefunded(): int {
        return $this->refunded;
    }
    
    public function setRefunded(int $refunded): void {
        $this->refunded = $refunded;
    }
}