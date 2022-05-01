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
    
     public function __toString() {
        return $this->number. ' '.$this->kontrahent->getName();
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $number;

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
    const STATUS_CART = 'offer';
    
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
     * @ORM\ManyToOne(targetEntity=Kontrahent::class, inversedBy="orders", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="kontrahent_id", referencedColumnName="id")
     */
    private $kontrahent;

    /**
     * @ORM\ManyToMany(targetEntity=Delivery::class, mappedBy="delivery_order")
     */
    private $deliveries;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $kontrahent_group;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pickup;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_pickup_wieliczka;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_extra_delivery;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $own_pickup;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $notice;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $count_pallets;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=PitchOrder::class, mappedBy="client_order")
     */
    private $pitchOrders;

    /**
     * @ORM\OneToMany(targetEntity=FactoryOrder::class, mappedBy="client_order")
     */
    private $factoryOrders;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $order_id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $car_price_netto;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $transport_in_price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $route_image_url;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="cart")
     */
    private $messages;




    public function __construct()
    {
        $this->relation = new ArrayCollection();
        $this->item = new ArrayCollection();
        $this->deliveries = new ArrayCollection();
        $this->pitchOrders = new ArrayCollection();
        $this->factoryOrders = new ArrayCollection();
        $this->messages = new ArrayCollection();
 
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

    public function addItem(OrderItem $item, $nknm = 1): self
    {
        $productName = $item->getProduct()->getName();
        
            if (strpos($productName, 'paleta') == false) {
                $quantity = $item->getQuantity();
                $packaging = $item->getProduct()->getPackaging();
                $floredpacks = floor($quantity / $packaging);
                $packs = $quantity / $packaging;
                $fullpacksquantity = $packaging*$floredpacks;
                $restquantity = $quantity - $fullpacksquantity;
                
                if($restquantity > 0){
                    $restItem = new OrderItem();
                    $restItem->setProduct($item->getProduct());
                    $restItem->setPrice(round($item->getPrice()*$nknm, 2));
                    $restItem->setQuantity($restquantity);
                    $restItem->setOrderRef($this);
                    $this->item[] = $restItem;
                }
            
            
            
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

       return round($total, 2);
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
           $delivery->addDeliveryOrder($this);
       }

       return $this;
   }

   public function removeDelivery(Delivery $delivery): self
   {
       if ($this->deliveries->removeElement($delivery)) {
           $delivery->removeDeliveryOrder($this);
       }

       return $this;
   }

   public function getKontrahentGroup(): ?string
   {
       return $this->kontrahent_group;
   }

   public function setKontrahentGroup(string $kontrahent_group): self
   {
       $this->kontrahent_group = $kontrahent_group;

       return $this;
   }

   public function getPickup(): ?string
   {
       return $this->pickup;
   }

   public function setPickup(string $pickup): self
   {
       $this->pickup = $pickup;

       return $this;
   }

   public function getIsPickupWieliczka(): ?bool
   {
       return $this->is_pickup_wieliczka;
   }

   public function setIsPickupWieliczka(?bool $is_pickup_wieliczka): self
   {
       $this->is_pickup_wieliczka = $is_pickup_wieliczka;

       return $this;
   }

   public function getIsExtraDelivery(): ?bool
   {
       return $this->is_extra_delivery;
   }

   public function setIsExtraDelivery(?bool $is_extra_delivery): self
   {
       $this->is_extra_delivery = $is_extra_delivery;

       return $this;
   }

   public function getOwnPickup(): ?bool
   {
       return $this->own_pickup;
   }

   public function setOwnPickup(?bool $own_pickup): self
   {
       $this->own_pickup = $own_pickup;

       return $this;
   }

   public function getType(): ?string
   {
       return $this->type;
   }

   public function setType(string $type): self
   {
       $this->type = $type;

       return $this;
   }

   public function getNotice(): ?string
   {
       return $this->notice;
   }

   public function setNotice(?string $notice): self
   {
       $this->notice = $notice;

       return $this;
   }

   public function getCountPallets(): ?bool
   {
       return $this->count_pallets;
   }

   public function setCountPallets(?bool $count_pallets): self
   {
       $this->count_pallets = $count_pallets;

       return $this;
   }

   public function getUser(): ?User
   {
       return $this->user;
   }

   public function setUser(?User $user): self
   {
       $this->user = $user;

       return $this;
   }

   /**
    * @return Collection|PitchOrder[]
    */
   public function getPitchOrders(): Collection
   {
       return $this->pitchOrders;
   }

   public function addPitchOrder(PitchOrder $pitchOrder): self
   {
       if (!$this->pitchOrders->contains($pitchOrder)) {
           $this->pitchOrders[] = $pitchOrder;
           $pitchOrder->setClientOrder($this);
       }

       return $this;
   }

   public function removePitchOrder(PitchOrder $pitchOrder): self
   {
       if ($this->pitchOrders->removeElement($pitchOrder)) {
           // set the owning side to null (unless already changed)
           if ($pitchOrder->getClientOrder() === $this) {
               $pitchOrder->setClientOrder(null);
           }
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
           $factoryOrder->setClientOrder($this);
       }

       return $this;
   }

   public function removeFactoryOrder(FactoryOrder $factoryOrder): self
   {
       if ($this->factoryOrders->removeElement($factoryOrder)) {
           // set the owning side to null (unless already changed)
           if ($factoryOrder->getClientOrder() === $this) {
               $factoryOrder->setClientOrder(null);
           }
       }

       return $this;
   }

   public function getOrderId(): ?int
   {
       return $this->order_id;
   }

   public function setOrderId(?int $order_id): self
   {
       $this->order_id = $order_id;

       return $this;
   }

   public function getCarPriceNetto(): ?bool
   {
       return $this->car_price_netto;
   }

   public function setCarPriceNetto(?bool $car_price_netto): self
   {
       $this->car_price_netto = $car_price_netto;

       return $this;
   }

   public function getTransportInPrice(): ?bool
   {
       return $this->transport_in_price;
   }

   public function setTransportInPrice(?bool $transport_in_price): self
   {
       $this->transport_in_price = $transport_in_price;

       return $this;
   }

   public function getRouteImageUrl(): ?string
   {
       return $this->route_image_url;
   }

   public function setRouteImageUrl(?string $route_image_url): self
   {
       $this->route_image_url = $route_image_url;

       return $this;
   }

   /**
    * @return Collection|Message[]
    */
   public function getMessages(): Collection
   {
       return $this->messages;
   }

   public function addMessage(Message $message): self
   {
       if (!$this->messages->contains($message)) {
           $this->messages[] = $message;
           $message->setCart($this);
       }

       return $this;
   }

   public function removeMessage(Message $message): self
   {
       if ($this->messages->removeElement($message)) {
           // set the owning side to null (unless already changed)
           if ($message->getCart() === $this) {
               $message->setCart(null);
           }
       }

       return $this;
   }

}
