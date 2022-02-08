<?php

namespace App\Entity;

use App\Repository\DriverRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DriverRepository::class)
 */
class Driver
{
    
    public function __toString() {
        return $this->name. ' '.$this->surname;
    }
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
    private $surname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $registration_number;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $car_wight;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $car_long;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $car_height;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_hds;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $axis;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $package_max_weight;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $max_pallets;

    /**
     * @ORM\ManyToMany(targetEntity=Transport::class, inversedBy="drivers")
     */
    private $prices;

    /**
     * @ORM\ManyToMany(targetEntity=Delivery::class, mappedBy="driver")
     */
    private $deliveries;

    public function __construct()
    {
        $this->prices = new ArrayCollection();
        $this->deliveries = new ArrayCollection();
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

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

    public function getRegistrationNumber(): ?string
    {
        return $this->registration_number;
    }

    public function setRegistrationNumber(string $registration_number): self
    {
        $this->registration_number = $registration_number;

        return $this;
    }

    public function getCarWight(): ?float
    {
        return $this->car_wight;
    }

    public function setCarWight(?float $car_wight): self
    {
        $this->car_wight = $car_wight;

        return $this;
    }

    public function getCarLong(): ?float
    {
        return $this->car_long;
    }

    public function setCarLong(?float $car_long): self
    {
        $this->car_long = $car_long;

        return $this;
    }

    public function getCarHeight(): ?float
    {
        return $this->car_height;
    }

    public function setCarHeight(?float $car_height): self
    {
        $this->car_height = $car_height;

        return $this;
    }

    public function getIsHds(): ?bool
    {
        return $this->is_hds;
    }

    public function setIsHds(?bool $is_hds): self
    {
        $this->is_hds = $is_hds;

        return $this;
    }

    public function getAxis(): ?int
    {
        return $this->axis;
    }

    public function setAxis(?int $axis): self
    {
        $this->axis = $axis;

        return $this;
    }

    public function getPackageMaxWeight(): ?float
    {
        return $this->package_max_weight;
    }

    public function setPackageMaxWeight(?float $package_max_weight): self
    {
        $this->package_max_weight = $package_max_weight;

        return $this;
    }

    public function getMaxPallets(): ?int
    {
        return $this->max_pallets;
    }

    public function setMaxPallets(?int $max_pallets): self
    {
        $this->max_pallets = $max_pallets;

        return $this;
    }

    /**
     * @return Collection|Transport[]
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function addPrice(Transport $price): self
    {
        if (!$this->prices->contains($price)) {
            $this->prices[] = $price;
        }

        return $this;
    }

    public function removePrice(Transport $price): self
    {
        $this->prices->removeElement($price);

        return $this;
    }

    /**
     * @return Collection|Delivery[]
     */
    public function getDeliveries(): Collection
    {
        return $this->deliveries;
    }

    public function addDelivery(Delivery $delivery): self
    {
        if (!$this->deliveries->contains($delivery)) {
            $this->deliveries[] = $delivery;
            $delivery->addDriver($this);
        }

        return $this;
    }

    public function removeDelivery(Delivery $delivery): self
    {
        if ($this->deliveries->removeElement($delivery)) {
            $delivery->removeDriver($this);
        }

        return $this;
    }
}
