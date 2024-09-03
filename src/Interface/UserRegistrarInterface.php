<?php

namespace App\Interface;

use App\DTO\UserDTO;

interface UserRegistrarInterface
{
    public function register(UserDTO $userDTO): JmsSerializable;
}
