<?php

namespace App\Entity;

use App\Repository\TransportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransportRepository::class)
 */
class Transport
{
    
    public function __toString() {
        return $this->name;
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
     * @ORM\Column(type="integer")
     */
    private $weight;

    /**
     * @ORM\Column(type="integer")
     */
    private $pallet_places;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_5;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_10;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_15;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_20;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_25;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_30;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_35;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_40;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_45;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_50;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_55;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_60;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_65;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_70;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_75;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_80;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_85;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_90;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_95;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_100;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $driver_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $registration_number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $notices;

    /**
     * @ORM\ManyToMany(targetEntity=Order::class, mappedBy="relation")
     */
    private $orders;

    /**
     * @ORM\ManyToMany(targetEntity=Driver::class, mappedBy="prices")
     */
    private $drivers;

    /**
     * @ORM\ManyToMany(targetEntity=FactoryOrder::class, mappedBy="driver")
     */
    private $factoryOrders;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $image;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->drivers = new ArrayCollection();
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

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getPalletPlaces(): ?int
    {
        return $this->pallet_places;
    }

    public function setPalletPlaces(int $pallet_places): self
    {
        $this->pallet_places = $pallet_places;

        return $this;
    }

    public function getPrice5(): ?int
    {
        return $this->price_5;
    }

    public function setPrice5(int $price_5): self
    {
        $this->price_5 = $price_5;

        return $this;
    }

    public function getPrice10(): ?int
    {
        return $this->price_10;
    }

    public function setPrice10(int $price_10): self
    {
        $this->price_10 = $price_10;

        return $this;
    }

    public function getPrice15(): ?int
    {
        return $this->price_15;
    }

    public function setPrice15(int $price_15): self
    {
        $this->price_15 = $price_15;

        return $this;
    }

    public function getPrice20(): ?int
    {
        return $this->price_20;
    }

    public function setPrice20(int $price_20): self
    {
        $this->price_20 = $price_20;

        return $this;
    }

    public function getPrice25(): ?int
    {
        return $this->price_25;
    }

    public function setPrice25(int $price_25): self
    {
        $this->price_25 = $price_25;

        return $this;
    }

    public function getPrice30(): ?int
    {
        return $this->price_30;
    }

    public function setPrice30(int $price_30): self
    {
        $this->price_30 = $price_30;

        return $this;
    }

    public function getPrice35(): ?int
    {
        return $this->price_35;
    }

    public function setPrice35(int $price_35): self
    {
        $this->price_35 = $price_35;

        return $this;
    }

    public function getPrice40(): ?int
    {
        return $this->price_40;
    }

    public function setPrice40(int $price_40): self
    {
        $this->price_40 = $price_40;

        return $this;
    }

    public function getPrice45(): ?int
    {
        return $this->price_45;
    }

    public function setPrice45(int $price_45): self
    {
        $this->price_45 = $price_45;

        return $this;
    }

    public function getPrice50(): ?int
    {
        return $this->price_50;
    }

    public function setPrice50(int $price_50): self
    {
        $this->price_50 = $price_50;

        return $this;
    }

    public function getPrice55(): ?int
    {
        return $this->price_55;
    }

    public function setPrice55(int $price_55): self
    {
        $this->price_55 = $price_55;

        return $this;
    }

    public function getPrice60(): ?int
    {
        return $this->price_60;
    }

    public function setPrice60(int $price_60): self
    {
        $this->price_60 = $price_60;

        return $this;
    }

    public function getPrice65(): ?int
    {
        return $this->price_65;
    }

    public function setPrice65(int $price_65): self
    {
        $this->price_65 = $price_65;

        return $this;
    }

    public function getPrice70(): ?int
    {
        return $this->price_70;
    }

    public function setPrice70(int $price_70): self
    {
        $this->price_70 = $price_70;

        return $this;
    }

    public function getPrice75(): ?int
    {
        return $this->price_75;
    }

    public function setPrice75(int $price_75): self
    {
        $this->price_75 = $price_75;

        return $this;
    }

    public function getPrice80(): ?int
    {
        return $this->price_80;
    }

    public function setPrice80(int $price_80): self
    {
        $this->price_80 = $price_80;

        return $this;
    }

    public function getPrice85(): ?int
    {
        return $this->price_85;
    }

    public function setPrice85(int $price_85): self
    {
        $this->price_85 = $price_85;

        return $this;
    }

    public function getPrice90(): ?int
    {
        return $this->price_90;
    }

    public function setPrice90(int $price_90): self
    {
        $this->price_90 = $price_90;

        return $this;
    }

    public function getPrice95(): ?int
    {
        return $this->price_95;
    }

    public function setPrice95(int $price_95): self
    {
        $this->price_95 = $price_95;

        return $this;
    }

    public function getPrice100(): ?int
    {
        return $this->price_100;
    }

    public function setPrice100(int $price_100): self
    {
        $this->price_100 = $price_100;

        return $this;
    }

    public function getDriverName(): ?string
    {
        return $this->driver_name;
    }

    public function setDriverName(string $driver_name): self
    {
        $this->driver_name = $driver_name;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getNotices(): ?string
    {
        return $this->notices;
    }

    public function setNotices(string $notices): self
    {
        $this->notices = $notices;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->addRelation($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            $order->removeRelation($this);
        }

        return $this;
    }

    /**
     * @return Collection|Driver[]
     */
    public function getDrivers(): Collection
    {
        return $this->drivers;
    }

    public function addDriver(Driver $driver): self
    {
        if (!$this->drivers->contains($driver)) {
            $this->drivers[] = $driver;
            $driver->addPrice($this);
        }

        return $this;
    }

    public function removeDriver(Driver $driver): self
    {
        if ($this->drivers->removeElement($driver)) {
            $driver->removePrice($this);
        }

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
            $factoryOrder->addDriver($this);
        }

        return $this;
    }

    public function removeFactoryOrder(FactoryOrder $factoryOrder): self
    {
        if ($this->factoryOrders->removeElement($factoryOrder)) {
            $factoryOrder->removeDriver($this);
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
