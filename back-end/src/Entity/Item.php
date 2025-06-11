<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity('App\Repository\ItemRepository')]
#[ORM\Table("items")]
class Item implements PersistableEntity {
    #[ORM\Column("id", Types::INTEGER)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column('name', Types::STRING, 100)]
    private ?string $name = null;

    #[ORM\Column('price', Types::INTEGER)]
    private ?int $price = null;

    #[ORM\ManyToOne(Team::class, inversedBy: 'items')]
    #[ORM\JoinColumn('team', 'id', nullable: false)]
    private Team $team;

    #[ORM\ManyToOne(Membre::class, inversedBy: 'items')]
    #[ORM\JoinColumn('manager', 'id', nullable: false)]
    private Membre $manager;

    #[ORM\OneToMany(Transaction::class, 'item', ['persist', 'remove'])]
    private Collection $transactions;

    public function __construct() {
        $this->transactions = new ArrayCollection();
    }

    public function toJson(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'manager' => $this->manager->getUserIdentifier()
        ];
    }

    /**
     * Get the value of id
     */ 
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }
    
    /**
     * Get the value of name
     */ 
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of price
     */ 
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */ 
    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of manager
     */ 
    public function getManager(): Membre
    {
        return $this->manager;
    }

    /**
     * Set the value of manager
     *
     * @return  self
     */ 
    public function setManager(Membre $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get the value of team
     */ 
    public function getTeam(): Team
    {
        return $this->team;
    }

    /**
     * Set the value of team
     *
     * @return  self
     */ 
    public function setTeam(Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get the value of transactions
     */ 
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    /**
     * Set the value of transactions
     *
     * @return  self
     */ 
    public function setTransactions(Collection $transactions): self
    {
        $this->transactions = $transactions;

        return $this;
    }
}