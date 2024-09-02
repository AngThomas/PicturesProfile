<?php

namespace App\Service\User;

use App\DTO\UserDTO;
use App\Entity\Photo;
use App\Entity\User;
use App\Exception\ValidationException;
use App\Model\PhotoDetails;
use App\Model\UserDetails;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class UserManager
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function makeNewUser(UserDTO $userDTO): User
    {
        $photoEntities = [];
        if ($this->userRepository->findOneBy(['email' => $userDTO->getEmail()])) {
            throw new BadCredentialsException('User already exists');
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

    public function saveNewUser(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function getUserDetails(User $user): UserDetails
    {
        $photos = $user->getPhotos()->toArray();
        $modelPhotos = [];
        if (!empty($photos)) {
            $modelPhotos = $this->convertToModels($photos);
        }

        return new UserDetails(
            $user->getEmail(),
            $user->getFullName(),
            $user->isActive(),
            $user->getAvatar(),
            $modelPhotos
        );
    }

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
