<?php

namespace App\DTO\JmsSerializable;

use App\Interface\JmsSerializable;
use JMS\Serializer\Annotation as Serializer;

class UserDetailsDTO implements JmsSerializable
{
    #[Serializer\Since(0.1)]
    private string $email;
    #[Serializer\Since(0.1)]
    private string $firstName;
    #[Serializer\Since(0.1)]
    private string $lastName;
    #[Serializer\Since(0.1)]
    private string $fullName;
    #[Serializer\Since(0.1)]
    private bool $active;
    #[Serializer\Since(0.1)]
    private string $avatar;
    #[Serializer\Since(0.1)]
    private array $photos;

    /**
     * @param PhotoDetailsDTO[] $photos
     */
    public function __construct(
        string $email,
        string $firstName,
        string $lastName,
        string $fullName,
        bool $active,
        string $avatar,
        array $photos,
    ) {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->fullName = $fullName;
        $this->active = $active;
        $this->avatar = $avatar;
        $this->photos = $photos;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getPhotos(): array
    {
        return $this->photos;
    }

    public function setPhotos(array $photos): void
    {
        $this->photos = $photos;
    }
}
