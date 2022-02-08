<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrderItemRepository::class)
 */
class OrderItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="item")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderRef;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pallets;
 
    /**
    * Tests if the given item given corresponds to the same order item.
    *
    * @param OrderItem $item
    *
    * @return bool
    */
   public function equals(OrderItem $item): bool
   {
       return $this->getProduct()->getId() === $item->getProduct()->getId();
   }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOrderRef(): ?Order
    {
        return $this->orderRef;
    }

    public function setOrderRef(?Order $orderRef): self
    {
        $this->orderRef = $orderRef;

        return $this;
    }
    
    /**
    * Calculates the item total.
    *
    * @return float|int
    */
   public function getTotal(): float
   {
       return $this->price * $this->getQuantity();
   }
   
   /**
    * Calculates the item total.
    *
    * @return float|int
    */
   public function getCatalogTotal(): float
   {
       return $this->getProduct()->getCatalogPrice() * $this->getQuantity();
   }

   public function getPrice(): ?float
   {
       return $this->price;
   }

   public function setPrice(float $price): self
   {
       $this->price = $price;

       return $this;
   }

   public function getPallets(): ?float
   {
       return $this->pallets;
   }

   public function setPallets(?float $pallets): self
   {
       $this->pallets = $pallets;

       return $this;
   }
   

}
