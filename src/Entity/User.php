<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email()]
    #[Assert\NotBlank()]
    private string $email;

    #[Assert\Length(min: 6, max: 50)]
    #[Assert\Regex(pattern: '/\d/', message: 'Password must contain at least one number.')]
    #[Assert\NotBlank()]
    private ?string $plainPassword;

    #[ORM\Column]
    private string $password;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Photo::class, cascade: ['persist'])]
    private Collection $photos;

    #[ORM\Column(length: 25, name: 'first_name')]
    #[Assert\Length(min: 2, max: 25)]
    #[Assert\NotBlank()]
    private string $firstName;

    #[ORM\Column(length: 25, name: 'last_name')]
    #[Assert\Length(min: 2, max: 25)]
    #[Assert\NotBlank()]
    private string $lastName;

    #[ORM\Column(length: 51, name: 'full_name')]
    private string $fullName;
    #[ORM\Column]
    private bool $active;

    #[ORM\Column(length: 255)]
    private string $avatar;

    #[ORM\Column(name: 'created_at', generated: 'INSERT')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column('updated_at', generated: 'ALWAYS')]
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        string $email,
        string $password,
        string $firstName,
        string $lastName,
        bool $active,
        string $avatar,
    ) {
        $this->email = $email;
        $this->plainPassword = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->fullName = "$firstName $lastName";
        $this->active = $active;
        $this->avatar = $avatar;
        $this->photos = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

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
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setUser($this);
        }

        return $this;
    }

    public function setPhotos(array $photos)
    {
        foreach ($photos as $photo) {
            $this->addPhoto($photo);
        }
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getUser() === $this) {
                $photo->setUser(null);
            }
        }

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    private function setFullName(): void
    {
        $this->fullName = $this->firstName.' '.$this->lastName;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getUsername(): string
    {
        return $this->email;
    }
}
