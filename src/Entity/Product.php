<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
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
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="products")
     */
    private $factory_order;

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

    public function getId(): ?int
    {
        return $this->id;
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
}
