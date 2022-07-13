<?php

namespace App\Entity;

use App\Repository\WarehouseDocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WarehouseDocumentRepository::class)
 */
class WarehouseDocument
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity=Kontrahent::class, inversedBy="warehouseDocuments")
     */
    private $kontrahent;

    /**
     * @ORM\ManyToMany(targetEntity=OrderItem::class, inversedBy="warehouseDocuments")
     */
    private $product_item;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="warehouseDocuments")
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity=Warehouse::class, inversedBy="warehouseDocuments")
     */
    private $warehouse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_brutto;

    public function __construct()
    {
        $this->product_item = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

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

    /**
     * @return Collection|OrderItem[]
     */
    public function getProductItem(): Collection
    {
        return $this->product_item;
    }

    public function addProductItem(OrderItem $productItem): self
    {
        if (!$this->product_item->contains($productItem)) {
            $this->product_item[] = $productItem;
        }

        return $this;
    }

    public function removeProductItem(OrderItem $productItem): self
    {
        $this->product_item->removeElement($productItem);

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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getWarehouse(): ?Warehouse
    {
        return $this->warehouse;
    }

    public function setWarehouse(?Warehouse $warehouse): self
    {
        $this->warehouse = $warehouse;

        return $this;
    }

    public function getIsBrutto(): ?bool
    {
        return $this->is_brutto;
    }

    public function setIsBrutto(bool $is_brutto): self
    {
        $this->is_brutto = $is_brutto;

        return $this;
    }
}
