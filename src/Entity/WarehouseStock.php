<?php

namespace App\Entity;

use App\Repository\WarehouseStockRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WarehouseStockRepository::class)
 */
class WarehouseStock
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
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="warehouse")
     */
    private $product;

    /**
     * @ORM\OneToOne(targetEntity=warehouse::class, cascade={"persist", "remove"})
     */
    private $relation;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getRelation(): ?warehouse
    {
        return $this->relation;
    }

    public function setRelation(?warehouse $relation): self
    {
        $this->relation = $relation;

        return $this;
    }
}
