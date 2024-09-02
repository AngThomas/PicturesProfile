<?php

namespace App\Service;

use App\Exception\ValidationException;
use App\Model\PhotoDetails;
use App\Service\File\FileNameGenerator;
use App\Service\File\FileValidator;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class FileProcessingService
{
    public const PUBLIC_PHOTOS_DIR = 'photos/';

    private FileValidator $fileValidator;
    private FileNameGenerator $fileNameGenerator;
    private DirectoryManager $directoryManager;

    public function __construct(
        FileValidator $fileValidator,
        FileNameGenerator $fileNameGenerator,
        DirectoryManager $directoryManager,
    ) {
        $this->fileValidator = $fileValidator;
        $this->fileNameGenerator = $fileNameGenerator;
        $this->directoryManager = $directoryManager;
    }

    /**
     * @param UploadedFile[] $files
     *
     * @return PhotoDetails[]
     *
     * @throws IOExceptionInterface
     * @throws ValidationException
     */
    public function uploadFiles(array $files, ?string $targetDir = null): array
    {
        $targetDir = $this->setSavePath($targetDir);

        $photoPaths = [];
        foreach ($files as $file) {
            $this->fileValidator->validate($file);
            $photoPaths[] = $this->saveFile($file, $targetDir);
        }

        return $photoPaths;
    }

    /**
     * @throws IOExceptionInterface
     */
    public function setSavePath(?string $targetDir = null): string
    {
        if (null === $targetDir) {
            $uniqueDirName = uniqid();
            $targetDir = self::PUBLIC_PHOTOS_DIR.$uniqueDirName;
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

        $uniqueFileName = $this->fileNameGenerator->createFileName($file->getClientOriginalName(), $file->guessExtension());

        $file->move($destination, $uniqueFileName);

        return new PhotoDetails(
            $uniqueFileName,
            $destination.'/'.$uniqueFileName
        );
    }

}
