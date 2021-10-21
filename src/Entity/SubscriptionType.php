<?php

namespace App\Entity;

use App\Repository\SubscriptionTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubscriptionTypeRepository::class)
 */
class SubscriptionType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $subscribName;

    /**
     * @ORM\Column(type="smallint")
     */
    private $duration;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $durationType;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="subscriptionType")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=SubscriptionHistory::class, mappedBy="subscriptionType")
     */
    private $subscriptionHistories;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->subscriptionHistories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubscribName(): ?string
    {
        return $this->subscribName;
    }

    public function setSubscribName(string $subscribName): self
    {
        $this->subscribName = $subscribName;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDurationType(): ?string
    {
        return $this->durationType;
    }

    public function setDurationType(string $durationType): self
    {
        $this->durationType = $durationType;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setSubscriptionType($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSubscriptionType() === $this) {
                $user->setSubscriptionType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SubscriptionHistory[]
     */
    public function getSubscriptionHistories(): Collection
    {
        return $this->subscriptionHistories;
    }

    public function addSubscriptionHistory(SubscriptionHistory $subscriptionHistory): self
    {
        if (!$this->subscriptionHistories->contains($subscriptionHistory)) {
            $this->subscriptionHistories[] = $subscriptionHistory;
            $subscriptionHistory->setSubscriptionType($this);
        }

        return $this;
    }

    public function removeSubscriptionHistory(SubscriptionHistory $subscriptionHistory): self
    {
        if ($this->subscriptionHistories->removeElement($subscriptionHistory)) {
            // set the owning side to null (unless already changed)
            if ($subscriptionHistory->getSubscriptionType() === $this) {
                $subscriptionHistory->setSubscriptionType(null);
            }
        }

        return $this;
    }
}
