<?php

namespace App\Model;

use JMS\Serializer\Annotation as Serializer;
class PhotoDetails
{
    private string $name;
    private string $url;

    public function __construct(
        #[Serializer\since(0.1)]
        string $name,
        #[Serializer\since(0.1)]
        string $url
    )
    {
        $this->name = $name;
        $this->url = $url;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }


}