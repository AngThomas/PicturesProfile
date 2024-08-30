<?php

namespace App\Service\User;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider\UserProviderFactoryInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserManager
{
    private UserRepository $userRepository;
    private UserProviderInterface $userProvider;
    public function __construct(
        UserRepository $userRepository,
        UserProviderInterface $userProvider
    )
    {
        $this->userRepository = $userRepository;
        $this->userProvider = $userProvider;
    }
    public function makeNewUser(UserDTO $userDTO): User
    {
        if ($this->userRepository->findOneBy(['email' => $userDTO->getEmail()]))
        {
            throw new BadCredentialsException('User already exists');
        }
        return new User(
            $userDTO->getEmail(),
            $userDTO->getPassword(),
            $userDTO->getFirstName(),
            $userDTO->getLastName(),
            $userDTO->isActive(),
            $userDTO->getAvatar()
        );
    }

    public function getUserByEmail(string $email): UserInterface
    {
        return $this->userProvider->loadUserByIdentifier($email);
    }
}