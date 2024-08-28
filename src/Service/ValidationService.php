<?php

namespace App\Service;

use Symfony\Component\Validator\Exception\ValidationFailedException;
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

    public function validate(mixed $value): void
    {
        $violatedConstraintsMessages = [];
        $errors = $this->validator->validate($value);

        foreach ($errors as $error) {
            $violatedConstraintsMessages[] = $error->getMessage();
        }

        if (!empty($violatedConstraintsMessages)) {
            throw new ValidationFailedException($value);
        }
    }
}