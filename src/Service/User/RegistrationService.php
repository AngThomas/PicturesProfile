<?php
// src/Service/RegistrationService.php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationService
{
    private $entityManager;
    private $passwordHasher;
    private $userRepository;
    private $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository,
        ValidatorInterface $validator
    ) {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function register(string $email, string $plainPassword): array
    {
        // Sprawdź, czy użytkownik już istnieje
        if ($this->userRepository->findOneBy(['email' => $email])) {
            throw new BadCredentialsException('User already exists');
        }

        // Utwórz nowego użytkownika
        $user = new User();

        // Walidacja
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return ['errors' => $errorMessages];
        }

        // Zapisz użytkownika
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return ['message' => 'User registered successfully'];
    }
}
