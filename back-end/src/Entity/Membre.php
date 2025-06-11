<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity('App\Repository\MembreRepository')]
#[ORM\Table("membre")]
#[UniqueEntity(fields: ['name'], message: 'There is already a membre with this name')]
class Membre implements UserInterface, PasswordAuthenticatedUserInterface, PersistableEntity {
    #[ORM\Column('id', Types::GUID, unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?string $id = null;

    #[ORM\Column("name", Types::STRING, 50, unique: true)]
    private ?string $name = null;
    
    #[ORM\Column("password", Types::STRING, 255)]
    private ?string $password = null;

    #[ORM\ManyToMany(Role::class, "membres")]
    private Collection $roles;

    #[ORM\OneToOne(Team::class, 'owner', cascade: ['persist'])]
    private ?Team $ownedTeam = null;

    #[ORM\ManyToOne(Team::class, inversedBy: 'membres')]
    #[ORM\JoinColumn('team', 'id', onDelete: 'CASCADE')]
    private ?Team $team;

    #[ORM\OneToMany(Item::class, 'manager', ['persist', 'remove'])]
    private Collection $items;

    #[ORM\OneToMany(Transaction::class, 'membre', ['persist', 'remove'])]
    private Collection $transactions;

    public function __construct() {
        $this->roles = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

    public function toJson(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'roles' => $this->getRoleLibelles(),
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
    public function getUserIdentifier(): string
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
     * Get the value of password
     */ 
    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getRoles(): array
    {
        $roles = [];
        foreach($this->roles as $role) {
            array_push($roles, $role->getName());
        }
        if(!in_array('ROLE_USER', $roles)) {
            array_push($roles, 'ROLE_USER');
        }
        return $roles;
    }

    /**
     * Get the value of password
     */ 
    public function getRoleLibelles(): array
    {
        $roles = [];
        foreach($this->roles as $role) {
            array_push($roles, $role->getLibelle());
        }
        if(!in_array('Membre', $roles)) {
            array_push($roles, 'Membre');
        }
        return $roles;
    }

    public function getRawRoles(): Collection {
        return $this->roles;
    }
    
    public function addRole(Role $role): self {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }

        return $this;
    }

    public function removeRole(Role $role): self {
        $this->roles->removeElement($role);
        return $this;
    }

    public function eraseCredentials(): void {
        return;
    }

    /**
     * Get the value of ownedTeam
     */ 
    public function getOwnedTeam(): ?Team
    {
        return $this->ownedTeam;
    }

    /**
     * Set the value of ownedTeam
     *
     * @return  self
     */ 
    public function setOwnedTeam(?Team $ownedTeam): self
    {
        $this->ownedTeam = $ownedTeam;

        return $this;
    }

    /**
     * Get the value of team
     */ 
    public function getTeam(): ?Team
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