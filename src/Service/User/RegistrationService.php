<?php

// src/Service/RegistrationService.php

namespace App\Service\User;

use App\DTO\UserDTO;
use App\Exception\ValidationException;
use App\Interface\RegistrationInterface;
use App\Service\UserPhotoManager;
use App\Service\ValidationService;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService implements RegistrationInterface
{
    private UserManager $userManager;
    private UserPhotoManager $userPhotoManager;
    private UserPasswordHasherInterface $passwordHasher;
    private ValidationService $validationService;

    public function __construct(
        UserManager                 $userManager,
        UserPhotoManager            $userPhotoManager,
        UserPasswordHasherInterface $passwordHasher,
        ValidationService           $validationService,
    ) {
        $this->userManager = $userManager;
        $this->userPhotoManager = $userPhotoManager;
        $this->passwordHasher = $passwordHasher;
        $this->validationService = $validationService;
    }

    /**
     * @throws ValidationException
     * @throws IOExceptionInterface
     */
    public function register(UserDTO $userDTO): bool
    {
        $photos = $this->userPhotoManager->uploadUserPhotos($userDTO->getFiles());
        $userDTO->setPhotos($photos);
        $user = $this->userManager->makeNewUser($userDTO);
        $this->validationService->validate($user);
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPlainPassword()));
        $user->eraseCredentials();
        $this->userManager->saveNewUser($user);

        return true;
    }
}
