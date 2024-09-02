<?php

namespace App\Model;

use JMS\Serializer\Annotation as Serializer;

class UserDetails
{
    private string $email;
    private string $firstName;
    private string $lastName;
    private string $fullName;
    private bool $active;
    private string $avatar;
    private array $photos;

    /**
     * @param PhotoDetails[] $photos
     */
    public function __construct(
        #[Serializer\Since(0.1)]
        string $email,
        #[Serializer\Since(0.1)]
        string $firstName,
        #[Serializer\Since(0.1)]
        string $lastName,
        #[Serializer\Since(0.1)]
        string $fullName,
        #[Serializer\Since(0.1)]
        bool $active,
        #[Serializer\Since(0.1)]
        string $avatar,
        #[Serializer\Since(0.1)]
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
