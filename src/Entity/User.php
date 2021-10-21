<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * 
     * @Assert\NotBlank(
     *  message="user.firstName.not_blank"
     * )
     * @Assert\Length(
     *  min=2,
     *  minMessage="user.firstName.length"
     * )
     * 
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
     * @ORM\Column(type="string", length=255, nullable=true)
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

    /**
     * @ORM\ManyToOne(targetEntity=SubscriptionType::class, inversedBy="users")
     */
    private $subscriptionType;

    /**
     * @ORM\OneToMany(targetEntity=SubscriptionHistory::class, mappedBy="subscriber")
     */
    private $subscriptionHistories;

    /**
     * @ORM\ManyToMany(targetEntity=Interest::class, inversedBy="users")
     */
    private $interests;

    public function __construct()
    {
        $this->subscriptionHistories = new ArrayCollection();
        $this->interests = new ArrayCollection();
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
        // Dates
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();

        //initial slug
        $this->setSlug(strtolower($this->getFirstName()));

        // boolean variables
        // set isActive to true
        $this->setIsActive(true);
        // set isSubscribed to false
        $this->setIsSubscribed(false);
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

    /**
     * Generation of slug with format firstname-id
     * Using the listener UserEntityListener.php (declared in services.yaml)
     * use for preUpdate and postPersist
     * 
     * @author Parmentier <fparmentier@happybiztrip.com>
     */
    public function defineSlug(SluggerInterface $slugger)
    {
        if ($this->slug !== $slugger->slug($this->firstName.' '.$this->id)->lower()) {
            $this->slug = $slugger->slug($this->firstName.' '.$this->id)->lower();
        }
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
    
    /**
     * Caclculate age with date of birth
     * Author : Frederic Parmentier
     * Created at : 2019/08/07
     *
     * @return void
     */
    public function getCalculateAge() {
        $today = new \DateTime('now');
        $age = $today->diff($this->getBirthDate());
        return $age->format('%y');
    }

     /**
     * Calculate the percentage of completed
     * 
     * @ORM\PreUpdate
     * 
     */
    public function computeCompleted() {
        $TOTAL_USER_OBJECT = 12;
        // by default, 3 objects fullfiled {firstName/email/Date of Birth}
        // object password not taken into account
        $userObjectCompleted=3;
        $this->getGender() != "" ? $userObjectCompleted++ : "";
        $this->getLastName() != "" ? $userObjectCompleted++ : "" ;
        $this->getSituation() != "" ? $userObjectCompleted++ : "" ;
        $this->getAvatar() != "" ? $userObjectCompleted++ : "" ;
        $this->getProfession() != "" ? $userObjectCompleted++ : "" ;
        $this->getCompany() != "" ? $userObjectCompleted++ : "" ;
        $this->getDescription() != "" ? $userObjectCompleted++ : "" ;
        // $this->getPhoneNumber() != "" ? $userObjectCompleted++ : "" ;
        // count($this->getInterests()) != 0 ? $userObjectCompleted++ : "";
        return $this->completed = round(($userObjectCompleted*100)/$TOTAL_USER_OBJECT);
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
            $subscriptionHistory->setSubscriber($this);
        }

        return $this;
    }

    public function removeSubscriptionHistory(SubscriptionHistory $subscriptionHistory): self
    {
        if ($this->subscriptionHistories->removeElement($subscriptionHistory)) {
            // set the owning side to null (unless already changed)
            if ($subscriptionHistory->getSubscriber() === $this) {
                $subscriptionHistory->setSubscriber(null);
            }
        }

        return $this;
    }

    /**
     * Calculate number of days remaining before the end of subscription
     * Author : F. Parmentier
     * Created At : 2019/08/24
     *
     * @return integer|null
     */
    public function getDaysEndOfSubscription(): ?int
    {
        $dateNow = new \Datetime('now');
        return intval($dateNow->diff($this->subscribEndAt)->format("%a"));
    }

    /**
     * @return Collection|Interest[]
     */
    public function getInterests(): Collection
    {
        return $this->interests;
    }

    public function addInterest(Interest $interest): self
    {
        if (!$this->interests->contains($interest)) {
            $this->interests[] = $interest;
        }

        return $this;
    }

    public function removeInterest(Interest $interest): self
    {
        $this->interests->removeElement($interest);

        return $this;
    }
}