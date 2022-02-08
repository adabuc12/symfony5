<?php

namespace App\Entity;

use App\Repository\ComplaintRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ComplaintRepository::class)
 */
class Complaint
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $notices;

    /**
     * @ORM\ManyToOne(targetEntity=Kontrahent::class, inversedBy="complaints")
     */
    private $kontrahent;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="complaints")
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $number;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNotices(): ?string
    {
        return $this->notices;
    }

    public function setNotices(?string $notices): self
    {
        $this->notices = $notices;

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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

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

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): self
    {
        $this->number = $number;

        return $this;
    }

    
}
