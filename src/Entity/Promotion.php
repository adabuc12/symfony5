<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 */
class Promotion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=ProductCategory::class, inversedBy="promotions")
     */
    private $product_category;

    /**
     * @ORM\Column(type="array")
     */
    private $price_types = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cart_condition;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $product_condition;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $price_condition;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_enabled;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $start_date;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $end_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cart_condition_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cart_condition_value;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $product_condition_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $product_condition_value;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $price_condition_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $price_condition_value;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $calculation_type;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $calculation_count_type;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $calculation_count_value;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $calculation_count_is_percent;

    public function __construct()
    {
        $this->product_category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|ProductCategory[]
     */
    public function getProductCategory(): Collection
    {
        return $this->product_category;
    }

    public function addProductCategory(ProductCategory $productCategory): self
    {
        if (!$this->product_category->contains($productCategory)) {
            $this->product_category[] = $productCategory;
        }

        return $this;
    }

    public function removeProductCategory(ProductCategory $productCategory): self
    {
        $this->product_category->removeElement($productCategory);

        return $this;
    }

    public function getPriceTypes(): ?array
    {
        return $this->price_types;
    }

    public function setPriceTypes(array $price_types): self
    {
        $this->price_types = $price_types;

        return $this;
    }

    public function getCartCondition(): ?string
    {
        return $this->cart_condition;
    }

    public function setCartCondition(string $cart_condition): self
    {
        $this->cart_condition = $cart_condition;

        return $this;
    }

    public function getProductCondition(): ?string
    {
        return $this->product_condition;
    }

    public function setProductCondition(?string $product_condition): self
    {
        $this->product_condition = $product_condition;

        return $this;
    }

    public function getPriceCondition(): ?string
    {
        return $this->price_condition;
    }

    public function setPriceCondition(string $price_condition): self
    {
        $this->price_condition = $price_condition;

        return $this;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->is_enabled;
    }

    public function setIsEnabled(bool $is_enabled): self
    {
        $this->is_enabled = $is_enabled;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(?\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(?\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getCartConditionType(): ?string
    {
        return $this->cart_condition_type;
    }

    public function setCartConditionType(?string $cart_condition_type): self
    {
        $this->cart_condition_type = $cart_condition_type;

        return $this;
    }

    public function getCartConditionValue(): ?string
    {
        return $this->cart_condition_value;
    }

    public function setCartConditionValue(?string $cart_condition_value): self
    {
        $this->cart_condition_value = $cart_condition_value;

        return $this;
    }

    public function getProductConditionType(): ?string
    {
        return $this->product_condition_type;
    }

    public function setProductConditionType(?string $product_condition_type): self
    {
        $this->product_condition_type = $product_condition_type;

        return $this;
    }

    public function getProductConditionValue(): ?string
    {
        return $this->product_condition_value;
    }

    public function setProductConditionValue(?string $product_condition_value): self
    {
        $this->product_condition_value = $product_condition_value;

        return $this;
    }

    public function getPriceConditionType(): ?string
    {
        return $this->price_condition_type;
    }

    public function setPriceConditionType(?string $price_condition_type): self
    {
        $this->price_condition_type = $price_condition_type;

        return $this;
    }

    public function getPriceConditionValue(): ?string
    {
        return $this->price_condition_value;
    }

    public function setPriceConditionValue(?string $price_condition_value): self
    {
        $this->price_condition_value = $price_condition_value;

        return $this;
    }

    public function getCalculationType(): ?string
    {
        return $this->calculation_type;
    }

    public function setCalculationType(string $calculation_type): self
    {
        $this->calculation_type = $calculation_type;

        return $this;
    }

    public function getCalculationCountType(): ?string
    {
        return $this->calculation_count_type;
    }

    public function setCalculationCountType(string $calculation_count_type): self
    {
        $this->calculation_count_type = $calculation_count_type;

        return $this;
    }

    public function getCalculationCountValue(): ?string
    {
        return $this->calculation_count_value;
    }

    public function setCalculationCountValue(string $calculation_count_value): self
    {
        $this->calculation_count_value = $calculation_count_value;

        return $this;
    }

    public function getCalculationCountIsPercent(): ?bool
    {
        return $this->calculation_count_is_percent;
    }

    public function setCalculationCountIsPercent(?bool $calculation_count_is_percent): self
    {
        $this->calculation_count_is_percent = $calculation_count_is_percent;

        return $this;
    }
}
