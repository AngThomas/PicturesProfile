<?php

namespace App\CustomExceptions;

use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends Exception
{
    private ConstraintViolationListInterface $violations;

    public function __construct( ConstraintViolationListInterface $violations)
    {
        $this->violations = $violations;
        parent::__construct('Validation error');
    }

    public function getViolations(): array
    {
        $errors=[];
        foreach ($this->violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();

        }
        return $errors;
    }
}