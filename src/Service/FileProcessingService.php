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
     * @return array<string>
     * @throws IOExceptionInterface
     * @throws ValidationException
     */
    public function uploadFiles(array $files): array
    {
        $photoPaths = [];
        foreach ($files as $file) {
            $this->validationService->validate($file, [
                new Assert\File([
                    'maxSize' => '2M',
                    'maxSizeMessage' => 'Maximum size allowed (2MB per file) exceeded.',
                    'mimeTypes' => ['image/jpeg', 'image/png'],
                    'mimeTypesMessage' => 'Please upload a valid JPEG or PNG file.',
                ]),
            ]);
            $photoPaths[] =$this->saveFile($file);
        }
        return $photoPaths;
    }

    /**
     * @throws IOExceptionInterface
     */
    private function saveFile(UploadedFile $file): string
    {
        if (null === $file->guessExtension()) {
            throw new \Exception();
        }

        $uniqueDirName = uniqid();
        $destination = self::PUBLIC_PHOTOS_DIR.$uniqueDirName;
        $uniqueFileName = $this->createFileName($file->getFilename(), $file->guessExtension());
        $this->directoryManager->ensureDirectoryExists($destination);
        $file->move($destination);

        return  $destination.'/'.$uniqueFileName;
    }

    private function createFileName(string $fileName, string $extension): string
    {
        $originalFilename = pathinfo($fileName, PATHINFO_FILENAME);
        $safeFilename = $this->generateUniqueFilename();
        return $safeFilename . '.' . $extension;
    }
    private function generateUniqueFileName(): string
    {
        return self::NAME_PREFIX.uniqid();
    }
}