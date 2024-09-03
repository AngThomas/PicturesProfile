<?php

namespace App\Handler\Exception;

use App\DTO\JmsSerializable\ExceptionDTO;
use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ValidationExceptionHandler extends KernelExceptionHandler
{
    public function getSupportedExceptions(): array
    {
        return [
            ValidationException::class,
        ];
    }

    public function handle(\Throwable $exception): Response
    {
        return $this->assembleResponse(
            new ExceptionDTO(
                'Validation error occured',
                $exception->getMessage(),
                $exception->getCode()
            )
        );
    }
}
