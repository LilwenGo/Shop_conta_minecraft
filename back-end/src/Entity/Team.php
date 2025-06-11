<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

#[ORM\Entity('App\Repository\TeamRepository')]
#[ORM\Table("team")]
class Team implements PersistableEntity {
    #[ORM\Column('id', Types::GUID, unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?string $id = null;

    #[ORM\Column("name", Types::STRING, 50)]
    private ?string $name = null;

    #[ORM\OneToOne(Membre::class, inversedBy: 'ownedTeam', cascade: ['persist'])]
    #[ORM\JoinColumn('owner', 'id', nullable: false, onDelete: 'CASCADE')]
    private Membre $owner;

    #[ORM\OneToMany(Membre::class, 'team', cascade: ['remove'], orphanRemoval: true)]
    private Collection $membres;
    
    #[ORM\OneToMany(Item::class, 'team', cascade: ['remove'])]
    private Collection $items;

    public function __construct() {
        $this->membres = new ArrayCollection();
        $this->items = new ArrayCollection();
    }

    public function toJson(): array {
        $membres = [];
        foreach($this->membres->toArray() as $membre) {
            array_push($membres, $membre->toJson());
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'owner' => $this->owner->getUserIdentifier(),
            'membres' => $membres
        ];
    }

    /**
     * Get the value of id
     */ 
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId(?string $id): self
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
     * Get the value of owner
     */ 
    public function getOwner(): Membre
    {
        return $this->owner;
    }

    /**
     * Set the value of owner
     *
     * @return  self
     */ 
    public function setOwner(Membre $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get the value of membres
     */ 
    public function getMembres(): Collection
    {
        return $this->membres;
    }

    /**
     * Set the value of membres
     *
     * @return  self
     */ 
    public function setMembres(Collection $membres): self
    {
        $this->membres = $membres;

        return $this;
    }

    /**
     * Get the value of items
     */ 
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * Set the value of items
     *
     * @return  self
     */ 
    public function setItems(Collection $items): self
    {
        $this->items = $items;

        return $this;
    }
}