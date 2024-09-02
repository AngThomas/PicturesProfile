<?php

namespace App\Service\User;

use App\DTO\UserDTO;
use App\Entity\Photo;
use App\Entity\User;
use App\Model\PhotoDetails;
use App\Model\UserDetails;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserManager
{
    private UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository,
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws CustomUserMessageAccountStatusException
     */
    public function makeNewUser(UserDTO $userDTO): User
    {
        $photoEntities = [];
        if ($this->userRepository->findOneBy(['email' => $userDTO->getEmail()])) {
            throw new CustomUserMessageAccountStatusException('User already exists');
        }

        $user = new User(
            $userDTO->getEmail(),
            $userDTO->getPassword(),
            $userDTO->getFirstName(),
            $userDTO->getLastName(),
            $userDTO->isActive(),
            $userDTO->getAvatar()
        );

        foreach ($userDTO->getPhotos() as $photo) {
            $photoEntities[] = new Photo(
                $photo->getName(),
                $photo->getUrl()
            );
        }

        $user->setPhotos($photoEntities);

        return $user;
    }

    public function getUserDetails(User $user): UserDetails
    {
        $photos = $user->getPhotos()->toArray();
        $modelPhotos = [];
        if (!empty($photos)) {
            $modelPhotos = PhotoDetails::convertToModels($photos);
        }

        return new UserDetails(
            $user->getEmail(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getFullName(),
            $user->isActive(),
            $user->getAvatar(),
            $modelPhotos
        );
    }

}
