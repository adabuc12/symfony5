<?php

namespace App\Entity;

use App\Repository\PitchOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PitchOrderRepository::class)
 */
class PitchOrder
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
     * @ORM\Column(type="datetime")
     */
    private $date_sended;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $number;

    /**
     * @ORM\OneToMany(targetEntity=OrderFactoryItem::class, mappedBy="pitchOrder")
     */
    private $relation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="pitchOrders")
     */
    private $created_by;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="pitchOrders")
     */
    private $client_order;

    public function __construct()
    {
        $this->relation = new ArrayCollection();
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
    public function getRelation(): Collection
    {
        return $this->relation;
    }

    public function addRelation(OrderFactoryItem $relation): self
    {
        if (!$this->relation->contains($relation)) {
            $this->relation[] = $relation;
            $relation->setPitchOrder($this);
        }

        return $this;
    }

    public function removeRelation(OrderFactoryItem $relation): self
    {
        if ($this->relation->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getPitchOrder() === $this) {
                $relation->setPitchOrder(null);
            }
        }

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

    public function getClientOrder(): ?Order
    {
        return $this->client_order;
    }

    public function setClientOrder(?Order $client_order): self
    {
        $this->client_order = $client_order;

        return $this;
    }
}
