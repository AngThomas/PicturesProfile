<?php

namespace App\Service;

use App\Exception\ValidationException;
use App\Model\PhotoDetails;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class FileProcessingService
{
    const PUBLIC_PHOTOS_DIR = 'photos/';

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
     * @param string|null $targetDir
     * @return array<string>
     * @throws IOExceptionInterface
     * @throws ValidationException
     */
    public function uploadFiles(array $files, ?string $targetDir = null): array
    {

        $targetDir = $this->setSavePath($targetDir);

        $photoPaths = [];
        foreach ($files as $file) {
            $this->validateFile($file);
            $photoPaths[] = $this->saveFile($file, $targetDir);
        }

        return $photoPaths;
    }

    public function setSavePath(?string $targetDir = null): string
    {
        if ($targetDir === null) {
            $uniqueDirName = uniqid();
            $targetDir = self::PUBLIC_PHOTOS_DIR . $uniqueDirName;
        }

        $this->directoryManager->setDirectoryPath($targetDir);
        $this->directoryManager->ensureDirectoryExists($targetDir);
        return $targetDir;
    }

    /**
     * @throws IOExceptionInterface|\Exception
     */
    public function saveFile(UploadedFile $file, string $destination): PhotoDetails
    {
        if (null === $file->guessExtension()) {
            throw new \Exception('Cannot determine file extension.');
        }

        $uniqueFileName = $this->createFileName($file->getClientOriginalName(), $file->guessExtension());

        $file->move($destination, $uniqueFileName);

        return new PhotoDetails(
            $uniqueFileName,
            $destination . '/' . $uniqueFileName
        );
    }

    /**
     * @throws ValidationException
     */
    public function validateFile(UploadedFile $file): void
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

    private function createFileName(string $fileName, string $extension): string
    {
        $originalFilename = pathinfo($fileName, PATHINFO_FILENAME);
        $safeFilename = $this->generateUniqueFileName($originalFilename);
        return $safeFilename . '.' . $extension;
    }

    private function generateUniqueFileName(string $originalFileName): string
    {
        return $originalFileName . uniqid();
    }
}
