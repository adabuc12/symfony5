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


    public function __construct()
    {
        $this->delivery_order = new ArrayCollection();
        $this->driver = new ArrayCollection();
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
}
