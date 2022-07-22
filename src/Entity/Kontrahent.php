<?php

namespace App\Entity;

use App\Repository\KontrahentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=KontrahentRepository::class)
 */
class Kontrahent
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Adress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nip;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $notices;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $post_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $class_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $group_name;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="kontrahent")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity=Payments::class, mappedBy="kontrahent")
     */
    private $payments;

    /**
     * @ORM\OneToMany(targetEntity=Complaint::class, mappedBy="kontrahent")
     */
    private $complaints;

    /**
     * @ORM\OneToMany(targetEntity=WarehouseDocument::class, mappedBy="kontrahent")
     */
    private $ProductItem;

    /**
     * @ORM\OneToMany(targetEntity=WarehouseDocument::class, mappedBy="kontrahent")
     */
    private $warehouseDocuments;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_customer;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_supplier;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->complaints = new ArrayCollection();
        $this->ProductItem = new ArrayCollection();
        $this->warehouseDocuments = new ArrayCollection();
    }






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

    public function getAdress(): ?string
    {
        return $this->Adress;
    }

    public function setAdress(?string $Adress): self
    {
        $this->Adress = $Adress;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNip(): ?string
    {
        return $this->nip;
    }

    public function setNip(?string $nip): self
    {
        $this->nip = $nip;

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

    public function getPostCode(): ?string
    {
        return $this->post_code;
    }

    public function setPostCode(?string $post_code): self
    {
        $this->post_code = $post_code;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getClassName(): ?string
    {
        return $this->class_name;
    }

    public function setClassName(string $class_name): self
    {
        $this->class_name = $class_name;

        return $this;
    }

    public function getGroupName(): ?string
    {
        return $this->group_name;
    }

    public function setGroupName(?string $group_name): self
    {
        $this->group_name = $group_name;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        // unset the owning side of the relation if necessary
        if ($order === null && $this->order !== null) {
            $this->order->setKontrahent(null);
        }

        // set the owning side of the relation if necessary
        if ($order !== null && $order->getKontrahent() !== $this) {
            $order->setKontrahent($this);
        }

        $this->order = $order;

        return $this;
    }

 

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setKontrahent($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getKontrahent() === $this) {
                $order->setKontrahent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * @return Collection|Payments[]
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payments $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->setKontrahent($this);
        }

        return $this;
    }

    public function removePayment(Payments $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getKontrahent() === $this) {
                $payment->setKontrahent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Complaint[]
     */
    public function getComplaints(): Collection
    {
        return $this->complaints;
    }

    public function addComplaint(Complaint $complaint): self
    {
        if (!$this->complaints->contains($complaint)) {
            $this->complaints[] = $complaint;
            $complaint->setKontrahent($this);
        }

        return $this;
    }

    public function removeComplaint(Complaint $complaint): self
    {
        if ($this->complaints->removeElement($complaint)) {
            // set the owning side to null (unless already changed)
            if ($complaint->getKontrahent() === $this) {
                $complaint->setKontrahent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|WarehouseDocument[]
     */
    public function getWarehouseDocuments(): Collection
    {
        return $this->warehouseDocuments;
    }

    public function addWarehouseDocument(WarehouseDocument $warehouseDocument): self
    {
        if (!$this->warehouseDocuments->contains($warehouseDocument)) {
            $this->warehouseDocuments[] = $warehouseDocument;
            $warehouseDocument->setKontrahent($this);
        }

        return $this;
    }

    public function removeWarehouseDocument(WarehouseDocument $warehouseDocument): self
    {
        if ($this->warehouseDocuments->removeElement($warehouseDocument)) {
            // set the owning side to null (unless already changed)
            if ($warehouseDocument->getKontrahent() === $this) {
                $warehouseDocument->setKontrahent(null);
            }
        }

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getIsCustomer(): ?bool
    {
        return $this->is_customer;
    }

    public function setIsCustomer(?bool $is_customer): self
    {
        $this->is_customer = $is_customer;

        return $this;
    }

    public function getIsSupplier(): ?bool
    {
        return $this->is_supplier;
    }

    public function setIsSupplier(?bool $is_supplier): self
    {
        $this->is_supplier = $is_supplier;

        return $this;
    }
    
}
