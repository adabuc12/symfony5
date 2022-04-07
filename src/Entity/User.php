<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    
    public function __toString() {
        return $this->name. ' '. $this->surname;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\ManyToMany(targetEntity=Task::class, mappedBy="user")
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="task_owner")
     */
    private $task_owner;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity=Payments::class, mappedBy="owner")
     */
    private $payments;

    /**
     * @ORM\OneToMany(targetEntity=Complaint::class, mappedBy="owner")
     */
    private $complaints;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="user")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity=FactoryOrder::class, mappedBy="created_by")
     */
    private $factoryOrders;

    /**
     * @ORM\OneToMany(targetEntity=PitchOrder::class, mappedBy="created_by")
     */
    private $pitchOrders;

    /**
     * @ORM\OneToMany(targetEntity=Notice::class, mappedBy="owner")
     */
    private $notices;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, mappedBy="notifyUserIfAvaible")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity=Log::class, mappedBy="created_by")
     */
    private $logs;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="created_by")
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity=FastDeal::class, mappedBy="owner")
     */
    private $fastDeals;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->task_owner = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->complaints = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->factoryOrders = new ArrayCollection();
        $this->pitchOrders = new ArrayCollection();
        $this->notices = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->logs = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->fastDeals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     * @return string the hashed password for this user
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->addUser($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            $task->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTaskOwner(): Collection
    {
        return $this->task_owner;
    }

    public function addTaskOwner(Task $taskOwner): self
    {
        if (!$this->task_owner->contains($taskOwner)) {
            $this->task_owner[] = $taskOwner;
            $taskOwner->setTaskOwner($this);
        }

        return $this;
    }

    public function removeTaskOwner(Task $taskOwner): self
    {
        if ($this->task_owner->removeElement($taskOwner)) {
            // set the owning side to null (unless already changed)
            if ($taskOwner->getTaskOwner() === $this) {
                $taskOwner->setTaskOwner(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

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
            $payment->setOwner($this);
        }

        return $this;
    }

    public function removePayment(Payments $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getOwner() === $this) {
                $payment->setOwner(null);
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
            $complaint->setOwner($this);
        }

        return $this;
    }

    public function removeComplaint(Complaint $complaint): self
    {
        if ($this->complaints->removeElement($complaint)) {
            // set the owning side to null (unless already changed)
            if ($complaint->getOwner() === $this) {
                $complaint->setOwner(null);
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

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
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
            $factoryOrder->setCreatedBy($this);
        }

        return $this;
    }

    public function removeFactoryOrder(FactoryOrder $factoryOrder): self
    {
        if ($this->factoryOrders->removeElement($factoryOrder)) {
            // set the owning side to null (unless already changed)
            if ($factoryOrder->getCreatedBy() === $this) {
                $factoryOrder->setCreatedBy(null);
            }
        }

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
            $pitchOrder->setCreatedBy($this);
        }

        return $this;
    }

    public function removePitchOrder(PitchOrder $pitchOrder): self
    {
        if ($this->pitchOrders->removeElement($pitchOrder)) {
            // set the owning side to null (unless already changed)
            if ($pitchOrder->getCreatedBy() === $this) {
                $pitchOrder->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notice[]
     */
    public function getNotices(): Collection
    {
        return $this->notices;
    }

    public function addNotice(Notice $notice): self
    {
        if (!$this->notices->contains($notice)) {
            $this->notices[] = $notice;
            $notice->setOwner($this);
        }

        return $this;
    }

    public function removeNotice(Notice $notice): self
    {
        if ($this->notices->removeElement($notice)) {
            // set the owning side to null (unless already changed)
            if ($notice->getOwner() === $this) {
                $notice->setOwner(null);
            }
        }

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
            $product->addNotifyUserIfAvaible($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeNotifyUserIfAvaible($this);
        }

        return $this;
    }

    /**
     * @return Collection|Log[]
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(Log $log): self
    {
        if (!$this->logs->contains($log)) {
            $this->logs[] = $log;
            $log->setCreatedBy($this);
        }

        return $this;
    }

    public function removeLog(Log $log): self
    {
        if ($this->logs->removeElement($log)) {
            // set the owning side to null (unless already changed)
            if ($log->getCreatedBy() === $this) {
                $log->setCreatedBy(null);
            }
        }

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
            $message->setCreatedBy($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getCreatedBy() === $this) {
                $message->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FastDeal[]
     */
    public function getFastDeals(): Collection
    {
        return $this->fastDeals;
    }

    public function addFastDeal(FastDeal $fastDeal): self
    {
        if (!$this->fastDeals->contains($fastDeal)) {
            $this->fastDeals[] = $fastDeal;
            $fastDeal->setOwner($this);
        }

        return $this;
    }

    public function removeFastDeal(FastDeal $fastDeal): self
    {
        if ($this->fastDeals->removeElement($fastDeal)) {
            // set the owning side to null (unless already changed)
            if ($fastDeal->getOwner() === $this) {
                $fastDeal->setOwner(null);
            }
        }

        return $this;
    }
}
