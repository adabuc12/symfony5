<?php

namespace App\Entity;

use App\Repository\FactoryOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FactoryOrderRepository::class)
 */
class FactoryOrder
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_sended;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="factoryOrders")
     */
    private $created_by;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $number;

    /**
     * @ORM\OneToMany(targetEntity=OrderFactoryItem::class, mappedBy="factory_order", cascade={"persist"})
     */
    private $orderFactoryItems;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="factoryOrders")
     */
    private $client_order;

    /**
     * @ORM\ManyToOne(targetEntity=Factory::class, inversedBy="factoryOrders")
     */
    private $factory;

    /**
     * @ORM\ManyToMany(targetEntity=Transport::class, inversedBy="factoryOrders")
     */
    private $driver;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $delivery_date;

    public function __construct()
    {
        $this->orderFactoryItems = new ArrayCollection();
        $this->driver = new ArrayCollection();
    }

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTimeInterface $date_created): self
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getDateSended(): ?\DateTimeInterface
    {
        return $this->date_sended;
    }

    public function setDateSended(\DateTimeInterface $date_sended): self
    {
        $this->date_sended = $date_sended;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
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
     * @return Collection|OrderFactoryItem[]
     */
    public function getOrderFactoryItems(): Collection
    {
        return $this->orderFactoryItems;
    }

    public function addOrderFactoryItem(OrderFactoryItem $orderFactoryItem): self
    {
        if (!$this->orderFactoryItems->contains($orderFactoryItem)) {
            $this->orderFactoryItems[] = $orderFactoryItem;
            $orderFactoryItem->setFactoryOrder($this);
        }

        return $this;
    }

    public function removeOrderFactoryItem(OrderFactoryItem $orderFactoryItem): self
    {
        if ($this->orderFactoryItems->removeElement($orderFactoryItem)) {
            // set the owning side to null (unless already changed)
            if ($orderFactoryItem->getFactoryOrder() === $this) {
                $orderFactoryItem->setFactoryOrder(null);
            }
        }

        return $this;
    }

    public function getClientOrder(): ?Order
    {
        return $this->client_order;
    }

    public function setClientOrder(?Order $client_order): self
    {
        $this->client_order = $client_order;

        return $this;
    }

    public function getFactory(): ?Factory
    {
        return $this->factory;
    }

    public function setFactory(?Factory $factory): self
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @return Collection|Transport[]
     */
    public function getDriver(): Collection
    {
        return $this->driver;
    }

    public function addDriver(Transport $driver): self
    {
        if (!$this->driver->contains($driver)) {
            $this->driver[] = $driver;
        }

        return $this;
    }

    public function removeDriver(Transport $driver): self
    {
        $this->driver->removeElement($driver);

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

}
