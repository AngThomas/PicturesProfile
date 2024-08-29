<?php

namespace App\Model;

use JMS\Serializer\Annotation as Serializer;
class RegistrationStatus
{
    const SUCCESS = 'User registered successfully!';
    const FAIL = 'Failed to register a user.';

    private bool $success;
    private string $description;
    public function __construct(
        #[Serializer\since(0.1)]
        bool $success,
        #[Serializer\since(0.1)]
        string $description
    )
    {
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