<?php

namespace App\Model;

use JMS\Serializer\Annotation as Serializer;
class LoginStatus
{
    private bool $success;
    private string $jwtToken;
    public function __construct(
        #[Serializer\since(0.1)]
        bool $success,
        #[Serializer\since(0.1)]
        string $jwtToken
    )
    {
        $this->success = $success;
        $this->jwtToken = $jwtToken;
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

    public function getJwtToken(): string
    {
        return $this->jwtToken;
    }

    public function setJwtToken(string $jwtToken): self
    {
        $this->jwtToken = $jwtToken;

        return $this;
    }


}