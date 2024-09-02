<?php

namespace App\Service\File;

use App\Exception\ValidationException;
use App\Service\ValidationService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;


class FileValidator
{
    private ValidationService $validationService;

    public function __construct(
        ValidationService $validationService,
    ) {
        $this->validationService = $validationService;
    }

    /**
     * @throws ValidationException
     */
    public function validate(UploadedFile $file): void
    {
        $this->validationService->validate($file, [
            new Assert\File([
                'maxSize' => '2M',
                'maxSizeMessage' => 'Maximum size allowed (2MB per file) exceeded.',
                'mimeTypes' => ['image/jpeg', 'image/png'],
                'mimeTypesMessage' => 'Please upload a valid JPEG or PNG file.',
            ]),
        ]);
    }
}
