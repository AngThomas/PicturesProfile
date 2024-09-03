<?php

namespace App\DTO\JmsSerializable;

use App\Interface\JmsSerializable;
use JMS\Serializer\Annotation as Serializer;

class RegistrationStatusDTO implements JmsSerializable
{
    public const SUCCESS = 'User registered successfully!';
    public const FAIL = 'Failed to register a user.';

    #[Serializer\Since(0.1)]
    private bool $success;
    #[Serializer\Since(0.1)]
    private string $description;

    public function __construct(
        bool $success,
        string $description,
    ) {
        $this->success = $success;
        $this->description = $description;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success): self
    {
        $this->success = $success;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
