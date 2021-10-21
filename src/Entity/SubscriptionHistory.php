<?php

namespace App\Entity;

use App\Repository\SubscriptionHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubscriptionHistoryRepository::class)
 */
class SubscriptionHistory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="subscriptionHistories")
     */
    private $subscriber;

    /**
     * @ORM\ManyToOne(targetEntity=SubscriptionType::class, inversedBy="subscriptionHistories")
     */
    private $subscriptionType;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $subscribPayAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $subscribBeginAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $subscribEndAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubscriber(): ?User
    {
        return $this->subscriber;
    }

    public function setSubscriber(?User $subscriber): self
    {
        $this->subscriber = $subscriber;

        return $this;
    }

    public function getSubscriptionType(): ?SubscriptionType
    {
        return $this->subscriptionType;
    }

    public function setSubscriptionType(?SubscriptionType $subscriptionType): self
    {
        $this->subscriptionType = $subscriptionType;

        return $this;
    }

    public function getSubscribPayAt(): ?\DateTimeImmutable
    {
        return $this->subscribPayAt;
    }

    public function setSubscribPayAt(\DateTimeImmutable $subscribPayAt): self
    {
        $this->subscribPayAt = $subscribPayAt;

        return $this;
    }

    public function getSubscribBeginAt(): ?\DateTimeImmutable
    {
        return $this->subscribBeginAt;
    }

    public function setSubscribBeginAt(\DateTimeImmutable $subscribBeginAt): self
    {
        $this->subscribBeginAt = $subscribBeginAt;

        return $this;
    }

    public function getSubscribEndAt(): ?\DateTimeImmutable
    {
        return $this->subscribEndAt;
    }

    public function setSubscribEndAt(\DateTimeImmutable $subscribEndAt): self
    {
        $this->subscribEndAt = $subscribEndAt;

        return $this;
    }
}
