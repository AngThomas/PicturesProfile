<?php

namespace App\Handler\Exception;

use App\DTO\JmsSerializable\ExceptionDTO;
use App\Exception\UserAlreadyExistsException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;


class UserExceptionHandler extends KernelExceptionHandler
{
    public function getSupportedExceptions(): array
    {
        return [
            UserNotFoundException::class,
            \Lexik\Bundle\JWTAuthenticationBundle\Exception\UserNotFoundException::class,
            UnsupportedUserException::class,
            UserAlreadyExistsException::class,
        ];
    }

    public function handle(\Throwable $exception): Response
    {
        return $this->assembleResponse(
            new ExceptionDTO(
                'User-related error occured',
                $exception->getMessage(),
                $exception->getCode()
            )
        );
    }
}
