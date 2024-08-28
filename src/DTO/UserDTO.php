<?php

namespace App\DTO;


use Symfony\Component\HttpFoundation\Request;

class UserDTO
{
    private string $email;
    private string $password;
    private string $firstName;
    private string $lastName;
    private bool $active;
    private string $avatar;

    public function __construct(
        string $email,
        string $password,
        string $firstName,
        string $lastName,
        bool $active,
        string $avatar
    )
    {
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->active = $active;
        $this->avatar = $avatar;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
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

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->request->get('email'),
            $request->request->get('password'),
            $request->request->get('firstName'),
            $request->request->get('lastName'),
            $request->request->get('active'),
            $request->request->get('avatar')
        );
    }


}