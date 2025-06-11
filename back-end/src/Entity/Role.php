<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity('App\Repository\RoleRepository')]
#[ORM\Table("role")]
#[UniqueEntity(fields: ['libelle', 'name'], message: "This role already exists, try changing the name or libelle")]
class Role implements PersistableEntity {
    #[ORM\Column("id", Types::INTEGER)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column("libelle", Types::STRING, 50, unique: true)]
    private ?string $libelle = null;
    
    #[ORM\Column("name", Types::STRING, 50, unique: true)]
    private ?string $name = null;

    #[ORM\ManyToMany(Membre::class, inversedBy: 'roles')]
    #[ORM\JoinTable("role_membre")]
    private Collection $membres;

    public function __construct() {
        $this->membres = new ArrayCollection();
    }

    public function toJson(): array {
        return [
            'id' => $this->id,
            'name' => $this->libelle
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
     * Get the value of libelle
     */ 
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    /**
     * Set the value of libelle
     *
     * @return  self
     */ 
    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

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

    public function addMembre(Membre $membre): self {
        if(!$this->membres->contains($membre)) {
            $this->membres->add($membre);
        }

        return $this;
    }
}