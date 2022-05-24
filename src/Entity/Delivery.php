<?php

namespace App\Entity;

use App\Repository\DeliveryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeliveryRepository::class)
 */
class Delivery
{
    
    public function __toString()
    {
        return $this->number;
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
    private $number;

    /**
     * @ORM\ManyToMany(targetEntity=Order::class, inversedBy="deliveries")
     */
    private $delivery_order;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $delivery_date;

    /**
     * @ORM\ManyToMany(targetEntity=Driver::class, inversedBy="deliveries")
     */
    private $driver;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $notices;

    /**
     * @ORM\ManyToMany(targetEntity=OrderItem::class, inversedBy="deliveries")
     */
    private $items;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pickup;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $delivery_adress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $second_pickup;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $second_delivery_adress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $transshipment;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_transshipment;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_courier;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $status;


    public function __construct()
    {
        $this->delivery_order = new ArrayCollection();
        $this->driver = new ArrayCollection();
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getDeliveryOrder(): Collection
    {
        return $this->delivery_order;
    }

    public function addDeliveryOrder(Order $deliveryOrder): self
    {
        if (!$this->delivery_order->contains($deliveryOrder)) {
            $this->delivery_order[] = $deliveryOrder;
        }

        return $this;
    }

    public function removeDeliveryOrder(Order $deliveryOrder): self
    {
        $this->delivery_order->removeElement($deliveryOrder);

        return $this;
    }

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->delivery_date;
    }

    public function setDeliveryDate(?\DateTimeInterface $delivery_date): self
    {
        $this->delivery_date = $delivery_date;

        return $this;
    }

    /**
     * @return Collection|Driver[]
     */
    public function getDriver(): Collection
    {
        return $this->driver;
    }

    public function addDriver(Driver $driver): self
    {
        if (!$this->driver->contains($driver)) {
            $this->driver[] = $driver;
        }

        return $this;
    }

    public function removeDriver(Driver $driver): self
    {
        $this->driver->removeElement($driver);

        return $this;
    }

    public function getNotices(): ?string
    {
        return $this->notices;
    }

    public function setNotices(?string $notices): self
    {
        $this->notices = $notices;

        return $this;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(OrderItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
        }

        return $this;
    }

    public function removeItem(OrderItem $item): self
    {
        $this->items->removeElement($item);

        return $this;
    }

    public function getPickup(): ?string
    {
        return $this->pickup;
    }

    public function setPickup(?string $pickup): self
    {
        $this->pickup = $pickup;

        return $this;
    }

    public function getDeliveryAdress(): ?string
    {
        return $this->delivery_adress;
    }

    public function setDeliveryAdress(?string $delivery_adress): self
    {
        $this->delivery_adress = $delivery_adress;

        return $this;
    }

    public function getSecondPickup(): ?string
    {
        return $this->second_pickup;
    }

    public function setSecondPickup(?string $second_pickup): self
    {
        $this->second_pickup = $second_pickup;

        return $this;
    }

    public function getSecondDeliveryAdress(): ?string
    {
        return $this->second_delivery_adress;
    }

    public function setSecondDeliveryAdress(?string $second_delivery_adress): self
    {
        $this->second_delivery_adress = $second_delivery_adress;

        return $this;
    }

    public function getTransshipment(): ?string
    {
        return $this->transshipment;
    }

    public function setTransshipment(?string $transshipment): self
    {
        $this->transshipment = $transshipment;

        return $this;
    }

    public function getIsTransshipment(): ?bool
    {
        return $this->is_transshipment;
    }

    public function setIsTransshipment(?bool $is_transshipment): self
    {
        $this->is_transshipment = $is_transshipment;

        return $this;
    }

    public function getIsCourier(): ?bool
    {
        return $this->is_courier;
    }

    public function setIsCourier(?bool $is_courier): self
    {
        $this->is_courier = $is_courier;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
