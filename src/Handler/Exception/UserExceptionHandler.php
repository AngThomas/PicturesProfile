<?php

namespace App\Handler\Exception;

use App\DTO\JmsSerializable\ExceptionDTO;
use App\Exception\UserAlreadyExistsException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class UserExceptionHandler extends KernelExceptionHandler
{
    public function getSupportedExceptions(): array
    {
        return [
            UserNotFoundException::class,
            \Lexik\Bundle\JWTAuthenticationBundle\Exception\UserNotFoundException::class,
            UnsupportedUserException::class,
            UserAlreadyExistsException::class,
            \RuntimeException::class,
        ];
    }

    public function handle(\Throwable $exception): Response
    {
        return $this->assembleResponse(
            new ExceptionDTO(
                'Error occured',
                $exception->getMessage(),
                $exception->getCode()
            )
        );
    }
}
