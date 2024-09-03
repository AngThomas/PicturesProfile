<?php

namespace App\Service\User;

use App\DTO\JmsSerializable\PhotoDetailsDTO;
use App\DTO\JmsSerializable\UserDetailsDTO;
use App\DTO\UserDTO;
use App\Entity\Photo;
use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class UserManager
{
    private UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository,
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws UserAlreadyExistsException
     */
    public function makeNewUser(UserDTO $userDTO): User
    {
        $photoEntities = [];
        if ($this->userRepository->findOneBy(['email' => $userDTO->getEmail()])) {
            throw new UserAlreadyExistsException('User already exists', Response::HTTP_INTERNAL_SERVER_ERROR);
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

    public function getUserDetails(string $identifier): UserDetailsDTO
    {
        $user = $this->userRepository->findOneBy(['email' => $identifier]);
        if (!isset($user)) {
            throw new UserNotFoundException('User has not been found.', Response::HTTP_NOT_FOUND);
        }
        $photos = $user->getPhotos()->toArray();
        $modelPhotos = [];
        if (!empty($photos)) {
            $modelPhotos = PhotoDetailsDTO::convertToModels($photos);
        }

        return new UserDetailsDTO(
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
