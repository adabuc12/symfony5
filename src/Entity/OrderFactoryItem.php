<?php

namespace App\Entity;

use App\Repository\OrderFactoryItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderFactoryItemRepository::class)
 */
class OrderFactoryItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=FactoryOrder::class, inversedBy="orderFactoryItems")
     */
    private $factory_order;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="orderFactoryItems")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=PitchOrder::class, inversedBy="relation")
     */
    private $pitchOrder;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_confirmed;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getFactoryOrder(): ?FactoryOrder
    {
        return $this->factory_order;
    }

    public function setFactoryOrder(?FactoryOrder $factory_order): self
    {
        $this->factory_order = $factory_order;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getPitchOrder(): ?PitchOrder
    {
        return $this->pitchOrder;
    }

    public function setPitchOrder(?PitchOrder $pitchOrder): self
    {
        $this->pitchOrder = $pitchOrder;

        return $this;
    }

    public function getIsConfirmed(): ?bool
    {
        return $this->is_confirmed;
    }

    public function setIsConfirmed(bool $is_confirmed): self
    {
        $this->is_confirmed = $is_confirmed;

        return $this;
    }
}
