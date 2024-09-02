<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;

class CredentialsDTO
{
    private string $email;
    private string $password;

    public function __construct(
        string $email,
        string $password,
    ) {
        $this->email = $email;
        $this->password = $password;
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

    public static function fromRequest(Request $request): CredentialsDTO
    {
        return new self(
            $request->request->get('email'),
            $request->request->get('password')
        );
    }
}
