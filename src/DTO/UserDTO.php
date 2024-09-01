<?php

namespace App\DTO;


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

    /**
     * @var UploadedFile[] $files
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
        ?string $avatar,
        array $files
    )
    {
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->active = $active;
        $this->avatar = $avatar;
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

    public function getFiles(): array
    {
        return $this->files;
    }


    /**
     * @param UploadedFile[] $files
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
            $request->request->get('avatar'),
            $request->files->all()
        );
    }


}