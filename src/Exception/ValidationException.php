<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \Exception
{
    private ConstraintViolationListInterface $violations;

    public function __construct(ConstraintViolationListInterface $violations)
    {
        $this->violations = $violations;
        parent::__construct($this->getViolations(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function getViolations(): string
    {
        $errors = [];
        foreach ($this->violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return implode(', ', $errors);
    }
}
