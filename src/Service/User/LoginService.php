<?php

namespace App\Service\User;

use App\DTO\CredentialsDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;

class LoginService
{
    private $userRepository;
    private $passwordHasher;
    private $jwtManager;
    private $entityManager;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager,
        EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->jwtManager = $jwtManager;
        $this->entityManager = $entityManager;
    }

    public function login(CredentialsDTO $credentialsDTO): UserInterface
    {
        $user = $this->userRepository->findOneBy(['email' => $credentialsDTO->getEmail()]);
        if (!$user || !$this->passwordHasher->isPasswordValid($user, $credentialsDTO->getPassword())) {
            throw new BadCredentialsException('Invalid email or password.');
        }

        return $user;
    }
}
