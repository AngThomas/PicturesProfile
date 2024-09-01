<?php

namespace App\Service;

use App\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{
    private ValidatorInterface $validator;
    public function __construct(
        ValidatorInterface $validator
    )
    {
        $this->validator = $validator;
    }

    /**
     * @throws ValidationException
     */
    public function validate(mixed $value, ?array $constraints = null): void
    {
        $violations = $this->validator->validate($value, $constraints);

        if (count($violations) > 0) {
            throw new ValidationException($violations);
        }
    }
}