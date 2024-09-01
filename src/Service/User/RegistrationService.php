<?php
// src/Service/RegistrationService.php

namespace App\Service\User;


use App\DTO\UserDTO;
use App\Entity\User;
use App\Exception\ValidationException;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RegistrationService
{
    private UserManager $userManager;
    private UserPasswordHasherInterface $passwordHasher;
    private ValidationService $validationService;

    public function __construct(
        UserManager $userManager,
        UserPasswordHasherInterface $passwordHasher,
        ValidationService $validationService
    ) {
        $this->userManager = $userManager;
        $this->passwordHasher = $passwordHasher;
        $this->validationService = $validationService;
    }

    /**
     * @throws ValidationException
     */
    public function register(UserDTO $userDTO): bool
    {
        $this->userManager->saveUserPhotos($userDTO);
        $user = $this->userManager->makeNewUser($userDTO);
        $this->validationService->validate($user);
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPlainPassword()));
        $user->eraseCredentials();
        $this->userManager->saveNewUser($user);
        return true;
    }
}
