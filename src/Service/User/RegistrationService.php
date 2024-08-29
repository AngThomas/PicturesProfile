<?php
// src/Service/RegistrationService.php

namespace App\Service\User;

use App\Entity\User;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RegistrationService
{
    private $entityManager;
    private $passwordHasher;
    private ValidationService $validationService;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        ValidationService $validationService
    ) {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->validationService = $validationService;
    }

    public function register(User $user): bool
    {
        $this->validationService->validate($user);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return true;
    }
}
