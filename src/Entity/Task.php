<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task
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
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_ended;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_to_end;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="tasks")
     */
    private $user;

    /**
     * @ORM\Column(type="smallint")
     */
    private $priorytet;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="task_owner")
     * @ORM\JoinColumn(nullable=false)
     */
    private $task_owner;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTimeInterface $date_created): self
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getDateEnded(): ?\DateTimeInterface
    {
        return $this->date_ended;
    }

    public function setDateEnded(?\DateTimeInterface $date_ended): self
    {
        $this->date_ended = $date_ended;

        return $this;
    }

    public function getDateToEnd(): ?\DateTimeInterface
    {
        return $this->date_to_end;
    }

    public function setDateToEnd(?\DateTimeInterface $date_to_end): self
    {
        $this->date_to_end = $date_to_end;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    public function getPriorytet(): ?int
    {
        return $this->priorytet;
    }

    public function setPriorytet(int $priorytet): self
    {
        $this->priorytet = $priorytet;

        return $this;
    }

    public function getTaskOwner(): ?User
    {
        return $this->task_owner;
    }

    public function setTaskOwner(?User $task_owner): self
    {
        $this->task_owner = $task_owner;

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
