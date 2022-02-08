<?php

namespace App\Entity;

use App\Repository\FactoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FactoryRepository::class)
 */
class Factory
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
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adress;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pitch_transport_price;

    /**
     * @ORM\OneToMany(targetEntity=FactoryOrder::class, mappedBy="factory")
     */
    private $factoryOrders;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $price_calculation_mode;

    public function __construct()
    {
        $this->factoryOrders = new ArrayCollection();
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPitchTransportPrice(): ?float
    {
        return $this->pitch_transport_price;
    }

    public function setPitchTransportPrice(?float $pitch_transport_price): self
    {
        $this->pitch_transport_price = $pitch_transport_price;

        return $this;
    }

    /**
     * @return Collection|FactoryOrder[]
     */
    public function getFactoryOrders(): Collection
    {
        return $this->factoryOrders;
    }

    public function addFactoryOrder(FactoryOrder $factoryOrder): self
    {
        if (!$this->factoryOrders->contains($factoryOrder)) {
            $this->factoryOrders[] = $factoryOrder;
            $factoryOrder->setFactory($this);
        }

        return $this;
    }

    public function removeFactoryOrder(FactoryOrder $factoryOrder): self
    {
        if ($this->factoryOrders->removeElement($factoryOrder)) {
            // set the owning side to null (unless already changed)
            if ($factoryOrder->getFactory() === $this) {
                $factoryOrder->setFactory(null);
            }
        }

        return $this;
    }

    public function getPriceCalculationMode(): ?string
    {
        return $this->price_calculation_mode;
    }

    public function setPriceCalculationMode(string $price_calculation_mode): self
    {
        $this->price_calculation_mode = $price_calculation_mode;

        return $this;
    }
}
