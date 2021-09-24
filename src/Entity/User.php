<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * 
 * @UniqueEntity {
 *  fields = ("email"),
 *  message = "user.email.unique"
 * }
 */

class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * 
     * @Assert\NotBlank(
     *  message = "user.email.not_blank"
     * )
     * 
     * @Assert\Email(
     *  message = "user.email.invalid"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(
     *  message = "user.password.not_blank"
     * )
     * 
     * @Assert\Length(
     *  min=8,
     *  minMessage = "user.password.length"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $role = [];

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=100)
     * 
     * @Assert\NotBlank(
     *  message = "user.firstName.not_blank"
     * )
     * 
     * @Assert\Length(
     *  min=2,
     *  minMessage = "user.firstName.length"
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="date")
     * 
     * @Assert\NotBlank(
     *  message = "user.birthDate.not_blank
     * )
     * 
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $situation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $profession;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $company;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSubscribed;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    /**
     * @ORM\Column(type="smallint")
     */
    private $completed;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $subscribPayAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $subscribBeginAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $subscribEndAt;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?array
    {
        return $this->role;
    }

    public function setRole(array $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getSituation(): ?string
    {
        return $this->situation;
    }

    public function setSituation(?string $situation): self
    {
        $this->situation = $situation;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

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

    public function getIsSubscribed(): ?bool
    {
        return $this->isSubscribed;
    }

    public function setIsSubscribed(bool $isSubscribed): self
    {
        $this->isSubscribed = $isSubscribed;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getCompleted(): ?int
    {
        return $this->completed;
    }

    public function setCompleted(int $completed): self
    {
        $this->completed = $completed;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSubscribPayAt(): ?\DateTimeImmutable
    {
        return $this->subscribPayAt;
    }

    public function setSubscribPayAt(?\DateTimeImmutable $subscribPayAt): self
    {
        $this->subscribPayAt = $subscribPayAt;

        return $this;
    }

    public function getSubscribBeginAt(): ?\DateTimeImmutable
    {
        return $this->subscribBeginAt;
    }

    public function setSubscribBeginAt(?\DateTimeImmutable $subscribBeginAt): self
    {
        $this->subscribBeginAt = $subscribBeginAt;

        return $this;
    }

    public function getSubscribEndAt(): ?\DateTimeImmutable
    {
        return $this->subscribEndAt;
    }

    public function setSubscribEndAt(?\DateTimeImmutable $subscribEndAt): self
    {
        $this->subscribEndAt = $subscribEndAt;

        return $this;
    }
}
