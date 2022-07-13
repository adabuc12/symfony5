<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
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
     * @ORM\Column(type="string", length=255)
     */
    private $Manufacture;

    /**
     * @ORM\Column(type="float")
     */
    private $packaging;

    /**
     * @ORM\Column(type="float")
     */
    private $package_weight;

    /**
     * @ORM\Column(type="float")
     */
    private $unit_weight;

    /**
     * @ORM\Column(type="float")
     */
    private $catalog_price;

    /**
     * @ORM\Column(type="float")
     */
    private $buy_price;

    /**
     * @ORM\Column(type="float")
     */
    private $sell_price_factory_detal;

    /**
     * @ORM\Column(type="float")
     */
    private $sell_price_pitch_detal;

    /**
     * @ORM\Column(type="float")
     */
    private $sell_price_factory_contractors;

    /**
     * @ORM\Column(type="float")
     */
    private $sell_price_pitch_contractors;

    /**
     * @ORM\Column(type="float")
     */
    private $sell_price_factory_wholesale;

    /**
     * @ORM\Column(type="float")
     */
    private $sell_price_pitch_wholesale;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_courier;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $courier_cost;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_not_available;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $estimated_availability_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $notices;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $sprzedaz_jednostkowa;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $width;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_on_promotion;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_on_palet;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_sell_cost;

    /**
     * @ORM\OneToMany(targetEntity=OrderFactoryItem::class, mappedBy="product")
     */
    private $orderFactoryItems;

    /**
     * @ORM\ManyToMany(targetEntity=ProductCategory::class, mappedBy="product")
     */
    private $productCategories;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $wpid;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="products")
     */
    private $notifyUserIfAvaible;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $update_date;

    /**
     * @ORM\OneToMany(targetEntity=WarehouseStock::class, mappedBy="product")
     */
    private $warehouse;

    public function __construct()
    {
        $this->orderFactoryItems = new ArrayCollection();
        $this->productCategories = new ArrayCollection();
        $this->notifyUserIfAvaible = new ArrayCollection();
        $this->warehouse = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setId(int $Id): self
    {
        $this->Id = $Id;

        return $this;
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

    public function getManufacture(): ?string
    {
        return $this->Manufacture;
    }

    public function setManufacture(string $Manufacture): self
    {
        $this->Manufacture = $Manufacture;

        return $this;
    }

    public function getPackaging(): ?float
    {
        return $this->packaging;
    }

    public function setPackaging(float $packaging): self
    {
        $this->packaging = $packaging;

        return $this;
    }

    public function getPackageWeight(): ?float
    {
        return $this->package_weight;
    }

    public function setPackageWeight(float $package_weight): self
    {
        $this->package_weight = $package_weight;

        return $this;
    }

    public function getUnitWeight(): ?float
    {
        return $this->unit_weight;
    }

    public function setUnitWeight(float $unit_weight): self
    {
        $this->unit_weight = $unit_weight;

        return $this;
    }

    public function getCatalogPrice(): ?float
    {
        return $this->catalog_price;
    }

    public function setCatalogPrice(float $catalog_price): self
    {
        $this->catalog_price = $catalog_price;

        return $this;
    }

    public function getBuyPrice(): ?float
    {
        return $this->buy_price;
    }

    public function setBuyPrice(float $buy_price): self
    {
        $this->buy_price = $buy_price;

        return $this;
    }

    public function getSellPriceFactoryDetal(): ?float
    {
        return $this->sell_price_factory_detal;
    }

    public function setSellPriceFactoryDetal(float $sell_price_factory_detal): self
    {
        $this->sell_price_factory_detal = $sell_price_factory_detal;

        return $this;
    }

    public function getSellPricePitchDetal(): ?float
    {
        return $this->sell_price_pitch_detal;
    }

    public function setSellPricePitchDetal(float $sell_price_pitch_detal): self
    {
        $this->sell_price_pitch_detal = $sell_price_pitch_detal;

        return $this;
    }

    public function getSellPriceFactoryContractors(): ?float
    {
        return $this->sell_price_factory_contractors;
    }

    public function setSellPriceFactoryContractors(float $sell_price_factory_contractors): self
    {
        $this->sell_price_factory_contractors = $sell_price_factory_contractors;

        return $this;
    }

    public function getSellPricePitchContractors(): ?float
    {
        return $this->sell_price_pitch_contractors;
    }

    public function setSellPricePitchContractors(float $sell_price_pitch_contractors): self
    {
        $this->sell_price_pitch_contractors = $sell_price_pitch_contractors;

        return $this;
    }

    public function getSellPriceFactoryWholesale(): ?float
    {
        return $this->sell_price_factory_wholesale;
    }

    public function setSellPriceFactoryWholesale(float $sell_price_factory_wholesale): self
    {
        $this->sell_price_factory_wholesale = $sell_price_factory_wholesale;

        return $this;
    }

    public function getSellPricePitchWholesale(): ?float
    {
        return $this->sell_price_pitch_wholesale;
    }

    public function setSellPricePitchWholesale(float $sell_price_pitch_wholesale): self
    {
        $this->sell_price_pitch_wholesale = $sell_price_pitch_wholesale;

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

    public function getCourierCost(): ?float
    {
        return $this->courier_cost;
    }

    public function setCourierCost(?float $courier_cost): self
    {
        $this->courier_cost = $courier_cost;

        return $this;
    }

    public function getIsNotAvailable(): ?bool
    {
        return $this->is_not_available;
    }

    public function setIsNotAvailable(?bool $is_not_available): self
    {
        $this->is_not_available = $is_not_available;

        return $this;
    }

    public function getEstimatedAvailabilityDate(): ?\DateTimeInterface
    {
        return $this->estimated_availability_date;
    }

    public function setEstimatedAvailabilityDate(?\DateTimeInterface $estimated_availability_date): self
    {
        $this->estimated_availability_date = $estimated_availability_date;

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

    public function getFactoryOrder(): ?Order
    {
        return $this->factory_order;
    }

    public function setFactoryOrder(?Order $factory_order): self
    {
        $this->factory_order = $factory_order;

        return $this;
    }

    public function getSprzedazJednostkowa(): ?float
    {
        return $this->sprzedaz_jednostkowa;
    }

    public function setSprzedazJednostkowa(?float $sprzedaz_jednostkowa): self
    {
        $this->sprzedaz_jednostkowa = $sprzedaz_jednostkowa;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(?float $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getIsOnPromotion(): ?bool
    {
        return $this->is_on_promotion;
    }

    public function setIsOnPromotion(?bool $is_on_promotion): self
    {
        $this->is_on_promotion = $is_on_promotion;

        return $this;
    }

    public function getIsOnPalet(): ?bool
    {
        return $this->is_on_palet;
    }

    public function setIsOnPalet(?bool $is_on_palet): self
    {
        $this->is_on_palet = $is_on_palet;

        return $this;
    }

    public function getIsSellCost(): ?bool
    {
        return $this->is_sell_cost;
    }

    public function setIsSellCost(?bool $is_sell_cost): self
    {
        $this->is_sell_cost = $is_sell_cost;

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
            $orderFactoryItem->setProduct($this);
        }

        return $this;
    }

    public function removeOrderFactoryItem(OrderFactoryItem $orderFactoryItem): self
    {
        if ($this->orderFactoryItems->removeElement($orderFactoryItem)) {
            // set the owning side to null (unless already changed)
            if ($orderFactoryItem->getProduct() === $this) {
                $orderFactoryItem->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductCategory[]
     */
    public function getProductCategories(): Collection
    {
        return $this->productCategories;
    }

    public function addProductCategory(ProductCategory $productCategory): self
    {
        if (!$this->productCategories->contains($productCategory)) {
            $this->productCategories[] = $productCategory;
            $productCategory->addProduct($this);
        }

        return $this;
    }

    public function removeProductCategory(ProductCategory $productCategory): self
    {
        if ($this->productCategories->removeElement($productCategory)) {
            $productCategory->removeProduct($this);
        }

        return $this;
    }

    public function getWpid(): ?int
    {
        return $this->wpid;
    }

    public function setWpid(?int $wpid): self
    {
        $this->wpid = $wpid;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getNotifyUserIfAvaible(): Collection
    {
        return $this->notifyUserIfAvaible;
    }

    public function addNotifyUserIfAvaible(User $notifyUserIfAvaible): self
    {
        if (!$this->notifyUserIfAvaible->contains($notifyUserIfAvaible)) {
            $this->notifyUserIfAvaible[] = $notifyUserIfAvaible;
        }

        return $this;
    }

    public function removeNotifyUserIfAvaible(User $notifyUserIfAvaible): self
    {
        $this->notifyUserIfAvaible->removeElement($notifyUserIfAvaible);

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->update_date;
    }

    public function setUpdateDate(?\DateTimeInterface $update_date): self
    {
        $this->update_date = $update_date;

        return $this;
    }

    /**
     * @return Collection|WarehouseStock[]
     */
    public function getWarehouse(): Collection
    {
        return $this->warehouse;
    }

    public function addWarehouse(WarehouseStock $warehouse): self
    {
        if (!$this->warehouse->contains($warehouse)) {
            $this->warehouse[] = $warehouse;
            $warehouse->setProduct($this);
        }

        return $this;
    }

    public function removeWarehouse(WarehouseStock $warehouse): self
    {
        if ($this->warehouse->removeElement($warehouse)) {
            // set the owning side to null (unless already changed)
            if ($warehouse->getProduct() === $this) {
                $warehouse->setProduct(null);
            }
        }

        return $this;
    }
}
