<?php

namespace App\Service\User;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class UserManager
{
    private UserRepository $userRepository;
    public function __construct(
        UserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
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
}