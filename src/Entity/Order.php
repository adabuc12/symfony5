<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
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
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="factory_order")
     */
    private $products;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_ordered;

    /**
     * @ORM\ManyToMany(targetEntity=Transport::class, inversedBy="orders")
     */
    private $relation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $delivery_date;

    /**
     * @ORM\OneToMany(targetEntity=OrderItem::class, mappedBy="orderRef", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $item;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status = self::STATUS_CART;

    /**
     * An order that is in progress, not placed yet.
     *
     * @var string
     */
    const STATUS_CART = 'cart';
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToOne(targetEntity=Kontrahent::class, inversedBy="order", cascade={"persist", "remove"})
     */
    private $kontrahent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $allowed_car_size;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->relation = new ArrayCollection();
        $this->item = new ArrayCollection();
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
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setFactoryOrder($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getFactoryOrder() === $this) {
                $product->setFactoryOrder(null);
            }
        }

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIsOrdered(): ?bool
    {
        return $this->is_ordered;
    }

    public function setIsOrdered(bool $is_ordered): self
    {
        $this->is_ordered = $is_ordered;

        return $this;
    }

    /**
     * @return Collection|Transport[]
     */
    public function getRelation(): Collection
    {
        return $this->relation;
    }

    public function addRelation(Transport $relation): self
    {
        if (!$this->relation->contains($relation)) {
            $this->relation[] = $relation;
        }

        return $this;
    }

    public function removeRelation(Transport $relation): self
    {
        $this->relation->removeElement($relation);

        return $this;
    }

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->delivery_date;
    }

    public function setDeliveryDate(\DateTimeInterface $delivery_date): self
    {
        $this->delivery_date = $delivery_date;

        return $this;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getItem(): Collection
    {
        return $this->item;
    }

    public function addItem(OrderItem $item): self
    {
        $productName = $item->getProduct()->getName();
        
            if (strpos($productName, 'paleta') == false) {
                $quantity = $item->getQuantity();
                $packaging = $item->getProduct()->getPackaging();
                $floredpacks = floor($quantity / $packaging);
                $packs = $quantity / $packaging;
                $fullpacksquantity = $packaging*$floredpacks;
                $restquantity = $quantity - $fullpacksquantity;
                $restItem = new OrderItem();
                $restItem->setProduct($item->getProduct());
                $restItem->setPrice(round($item->getPrice()*1.25, 2));
                $restItem->setQuantity($restquantity);
                $restItem->setOrderRef($this);
                $this->item[] = $restItem;

            
            
            
                foreach ($this->getItem() as $existingItem ) {
                    if ($existingItem->equals($item) && $item->getPrice() == $existingItem->getPrice()) {
                        $existingItem->setQuantity(
                            $existingItem->getQuantity() + $fullpacksquantity
                        );
                        return $this;
                    }
                }
                if($fullpacksquantity > 0){
                    $item->setQuantity($fullpacksquantity);
                    $this->item[] = $item;
                    $item->setOrderRef($this);
                }
                return $this;
            }else{
                foreach ($this->getItem() as $existingItem ) {
                     if ($existingItem->equals($item)) {
                         $existingItem->setQuantity(
                             $existingItem->getQuantity() + $item->getQuantity()
                         );
                         return $this;
                     }
                 }
                 $this->item[] = $item;
                 $item->setOrderRef($this); 
             }
        return $this;
    }
    /**
    * Removes all item from the order.
    *
    * @return $this
    */
   public function removeItems(): self
   {
       foreach ($this->getItem() as $item) {
           $this->removeItem($item);
       }

       return $this;
   }

    public function removeItem(OrderItem $item): self
    {

        if ($this->item->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getOrderRef() === $this) {
                $item->setOrderRef(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    
    /**
    * Calculates the order total.
    *
    * @return float
    */
   public function getTotal(): float
   {
       $total = 0;

       foreach ($this->getItem() as $item) {
           $total += $item->getTotal();
       }

       return $total;
   }

   public function getAdress(): ?string
   {
       return $this->adress;
   }

   public function setAdress(?string $adress): self
   {
       $this->adress = $adress;

       return $this;
   }

   public function getPhone(): ?string
   {
       return $this->phone;
   }

   public function setPhone(?string $phone): self
   {
       $this->phone = $phone;

       return $this;
   }

   public function getKontrahent(): ?Kontrahent
   {
       return $this->kontrahent;
   }

   public function setKontrahent(?Kontrahent $kontrahent): self
   {
       $this->kontrahent = $kontrahent;

       return $this;
   }

   public function getAllowedCarSize(): ?string
   {
       return $this->allowed_car_size;
   }

   public function setAllowedCarSize(?string $allowed_car_size): self
   {
       $this->allowed_car_size = $allowed_car_size;

       return $this;
   }
}
