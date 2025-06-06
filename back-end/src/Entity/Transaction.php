<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity('App\Repository\TransactionRepository')]
#[ORM\Table("transaction")]
class Transaction implements PersistableEntity {
    #[ORM\ManyToOne(Membre::class, inversedBy: "transactions")]
    #[ORM\JoinColumn('membre', "id", nullable: false)]
    #[ORM\Id]
    private Membre $membre;

    #[ORM\ManyToOne(Item::class, inversedBy: "transactions")]
    #[ORM\JoinColumn('item', "id", nullable: false)]
    #[ORM\Id]
    private Item $item;

    #[ORM\Column('sum', Types::INTEGER)]
    private ?int $sum = null;
    
    #[ORM\Column('refunded_sum', Types::INTEGER)]
    private ?int $refundedSum = null;

    public function getId(): void {
        return;
    }

    /**
     * Get the value of sum
     */ 
    public function getSum(): ?int
    {
        return $this->sum;
    }

    /**
     * Set the value of sum
     *
     * @return  self
     */ 
    public function setSum(?int $sum): self
    {
        $this->sum = $sum;

        return $this;
    }

    /**
     * Get the value of refundedSum
     */ 
    public function getRefundedSum(): ?int
    {
        return $this->refundedSum;
    }

    /**
     * Set the value of refundedSum
     *
     * @return  self
     */ 
    public function setRefundedSum(?int $refundedSum): self
    {
        $this->refundedSum = $refundedSum;

        return $this;
    }

    /**
     * Get the value of membre
     */ 
    public function getMembre(): Membre
    {
        return $this->membre;
    }

    /**
     * Set the value of membre
     *
     * @return  self
     */ 
    public function setMembre(Membre $membre): self
    {
        $this->membre = $membre;

        return $this;
    }

    /**
     * Get the value of item
     */ 
    public function getItem(): Item
    {
        return $this->item;
    }

    /**
     * Set the value of item
     *
     * @return  self
     */ 
    public function setItem(Item $item): self
    {
        $this->item = $item;

        return $this;
    }
}