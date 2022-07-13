<?php

namespace App\Entity;

use App\Repository\WarehouseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WarehouseRepository::class)
 */
class Warehouse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $postCode;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="activeWarehause")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=WarehouseDocument::class, mappedBy="warehouse")
     */
    private $warehouseDocuments;
    
    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->warehouseDocuments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function setPostCode(string $postCode): self
    {
        $this->postCode = $postCode;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setActiveWarehause($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getActiveWarehause() === $this) {
                $user->setActiveWarehause(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|WarehouseDocument[]
     */
    public function getWarehouseDocuments(): Collection
    {
        return $this->warehouseDocuments;
    }

    public function addWarehouseDocument(WarehouseDocument $warehouseDocument): self
    {
        if (!$this->warehouseDocuments->contains($warehouseDocument)) {
            $this->warehouseDocuments[] = $warehouseDocument;
            $warehouseDocument->setWarehouse($this);
        }

        return $this;
    }

    public function removeWarehouseDocument(WarehouseDocument $warehouseDocument): self
    {
        if ($this->warehouseDocuments->removeElement($warehouseDocument)) {
            // set the owning side to null (unless already changed)
            if ($warehouseDocument->getWarehouse() === $this) {
                $warehouseDocument->setWarehouse(null);
            }
        }

        return $this;
    }
}
