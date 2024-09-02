<?php

namespace App\DTO;

use App\Model\PhotoDetails;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class UserDTO
{
    private string $email;
    private string $password;
    private string $firstName;
    private string $lastName;
    private bool $active;
    private ?string $avatar;

    private array $photos;

    /**
     * @var UploadedFile[]
     */
    private array $files;

    /**
     * @param UploadedFile[] $files
     */
    public function __construct(
        string $email,
        string $password,
        string $firstName,
        string $lastName,
        bool $active,
        array $files,
        string $avatar = 'public/photos/default/dummy.jpg',
        array $photos = [],
    ) {
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->active = $active;
        $this->avatar = $avatar;
        $this->photos = $photos;

        $this->files = $files;
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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return PhotoDetails[] $photos
     */
    public function getPhotos(): array
    {
        return $this->photos;
    }

    public function addPhoto(PhotoDetails $photo)
    {
        $this->photos[] = $photo;
    }

    public function setPhotos(array $photos): void
    {
        foreach ($photos as $photo) {
            $this->photos[] = $photo;
        }
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param UploadedFile[] $files
     *
     * @return $this
     */
    public function setFiles(array $files): self
    {
        $this->files = $files;

        return $this;
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->request->get('email'),
            $request->request->get('password'),
            $request->request->get('firstName'),
            $request->request->get('lastName'),
            $request->request->get('active'),
            $request->files->all(),
        );
    }
}
