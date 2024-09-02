<?php

// src/Service/RegistrationService.php

namespace App\Service\User;

use App\DTO\UserDTO;
use App\Exception\ValidationException;
use App\Interface\RegistrationInterface;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService implements RegistrationInterface
{
    private UserManager $userManager;
    private UserPhotoManager $userPhotoManager;
    private UserPasswordHasherInterface $passwordHasher;
    private ValidationService $validationService;
    private EntityManagerInterface $entityManager;

    public function __construct(
        UserManager $userManager,
        UserPhotoManager $userPhotoManager,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        ValidationService $validationService,
    ) {
        $this->userManager = $userManager;
        $this->userPhotoManager = $userPhotoManager;
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
        $this->validationService = $validationService;
    }

    /**
     * @throws ValidationException
     * @throws IOExceptionInterface
     */
    public function register(UserDTO $userDTO): bool
    {
        $this->userPhotoManager->uploadUserPhotos($userDTO);
        $user = $this->userManager->makeNewUser($userDTO);
        $this->validationService->validate($user);
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPlainPassword()));
        $user->eraseCredentials();
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return true;
    }
}
