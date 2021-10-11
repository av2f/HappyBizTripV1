<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * 
 * @UniqueEntity (
 *  fields = {"email"},
 *  message = "user.email.unique"
 * )
 * 
 * @UniqueEntity (
 *  fields = {"slug"}
 * )
 * 
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * 
     * @Assert\NotBlank(
     *  message = "user.email.not_blank"
     * )
     * 
     * @Assert\Email(
     *  message = "user.email.invalid"
     * )
     * 
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * 
     * @Assert\NotBlank(
     *  message="user.password.not_blank"
     * )
     * @Assert\Length(
     *  min=8,
     *  minMessage="user.password.length"
     * )
     * 
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="date")
     * 
     * @Assert\NotBlank(
     *  message="user.birthDate.not_null"
     * )
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    // lifecycleCallbacks functions
    /**
     * Setup default avatar image if not defined
     * boolean variables and creation/update date
     * 
     * @ORM\PrePersist
     * 
     * @return void
     */
    public function setInitialUser() 
    {
        // Avatar
        if ($this->getAvatar() === "") {
            $this->setAvatar("defaultAvatar.png");
        }

        // Dates
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();

        // boolean variables
        // set isActive to true
        $this->setIsActive(true);
        // set isSubscribed to false
        // $this->setIsSubscribed(false);
        // set isDeleted to false
        $this->setIsDeleted(false);
        
        // set % completed of profile
        $this->setCompleted(25);
    }

    /**
     * Generate the date of last modified date of profile in preUpdate
     * 
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function setUpdatedAtDate()
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}