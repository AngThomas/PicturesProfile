<?php

namespace App\Service;

use App\CustomExceptions\ValidationException;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FileProcessingService
{

    const PUBLIC_PHOTOS_DIR = 'public/photos/';
    const NAME_PREFIX = 'usrPic';
    private ValidationService $validationService;
    private DirectoryManager $directoryManager;

    public function __construct(
        ValidationService $validationService,
        DirectoryManager $directoryManager
    )
    {
        $this->validationService = $validationService;
        $this->directoryManager = $directoryManager;
    }

    /**
     * @param UploadedFile[] $files
     * @return void
     * @throws ValidationException
     * @throws IOExceptionInterface
     */
    public function uploadFiles(array $files)
    {
        foreach ($files as $file) {
            $this->validationService->validate($file, [
                new Assert\File([
                    'maxSize' => '2M',
                    'maxSizeMessage' => 'Maximum size allowed (2MB per file) exceeded.',
                    'mimeTypes' => ['image/jpeg', 'image/png'],
                    'mimeTypesMessage' => 'Please upload a valid JPEG or PNG file.',
                ]),
            ]);
            $this->saveFile($file);
        }
    }

    /**
     * @throws IOExceptionInterface
     */
    private function saveFile(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->generateUniqueFilename($originalFilename);
        $newFilename = $safeFilename . '.' . $file->guessExtension();
        $uniqueDirName = uniqid();
        $this->directoryManager->ensureDirectoryExists(self::PUBLIC_PHOTOS_DIR.$uniqueDirName);

    }

    private function generateUniqueFileName(): string
    {
        return self::NAME_PREFIX.uniqid();
    }
}