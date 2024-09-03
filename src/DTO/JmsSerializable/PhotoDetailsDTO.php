<?php

namespace App\DTO\JmsSerializable;

use App\Entity\Photo;
use App\Interface\JmsSerializable;
use JMS\Serializer\Annotation as Serializer;

class PhotoDetailsDTO implements JmsSerializable
{
    private string $name;
    private string $url;

    public function __construct(
        #[Serializer\Since(0.1)]
        string $name,
        #[Serializer\Since(0.1)]
        string $url,
    ) {
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

    /**
     * @param Photo $photos
     * @return PhotoDetailsDTO[]
     */
    public static function convertToModels(array $photos): array
    {
        $modelPhotos = [];
        foreach ($photos as $photo) {
            $modelPhotos[] = new self(
                $photo->getName(),
                $photo->getUrl()
            );
        }

        return $modelPhotos;
    }
}
