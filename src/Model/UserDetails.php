<?php

namespace App\Model;

use JMS\Serializer\Annotation as Serializer;

class UserDetails
{
    private string $email;
    private string $fullName;
    private bool $active;
    private string $avatar;
    private array $photos;

    /**
     * @param PhotoDetails[] $photos
     */
    public function __construct(
        #[Serializer\since(0.1)]
         string $email,
        #[Serializer\since(0.1)]
         string $fullName,
        #[Serializer\since(0.1)]
         bool $active,
        #[Serializer\since(0.1)]
         string $avatar,
        #[Serializer\since(0.1)]
         array $photos
    )
    {
        $this->email = $email;
        $this->fullName = $fullName;
        $this->active = $active;
        $this->avatar = $avatar;
        $this->photos = $photos;
    }

    /**
     * @param PhotoDetails[] $photos
     */
    private function convertToModels(array $photos): array
    {
        $modelPhotos = [];
        foreach ($photos as $photo) {
            $modelPhotos[] = new PhotoDetails(
                $photo->getName(),
                $photo->getUrl()
            );
        }
        return $modelPhotos;
    }
}