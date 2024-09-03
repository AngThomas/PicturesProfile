<?php

namespace App\Service\File;

use App\Exception\ValidationException;
use App\Service\ValidationService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Yaml\Yaml;

class FileValidator
{
    private ValidationService $validationService;
    private array $rules;

    public function __construct(
        ValidationService $validationService,
        $validationRulesPath,
    ) {
        $this->validationService = $validationService;
        $this->rules = Yaml::parseFile($validationRulesPath)['file_validation'];
    }

    /**
     * @throws ValidationException
     */
    public function validate(UploadedFile $file): void
    {
        $this->validationService->validate($file, [
            new Assert\File(
                $this->rules
            ),
        ]);
    }
}
