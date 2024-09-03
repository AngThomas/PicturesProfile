<?php

namespace App\DTO\JmsSerializable;

use App\Interface\JmsSerializable;
use JMS\Serializer\Annotation as Serializer;

class ExceptionDTO implements JmsSerializable
{
    #[Serializer\Since(0.1)]
    private string $title;
    #[Serializer\Since(0.1)]
    private string $details;
    #[Serializer\Since(0.1)]
    private int $statusCode;

    public function __construct(
        string $title,
        string $details,
        int $statusCode,
    ) {
        $this->title = $title;
        $this->details = $details;
        $this->statusCode = $statusCode;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDetails(): string
    {
        return $this->details;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }
}
