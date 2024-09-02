<?php

namespace App\Service;

use App\Exception\ValidationException;
use App\Model\PhotoDetails;
use App\Service\File\FileNameGenerator;
use App\Service\File\FileUploader;
use App\Service\File\FileValidator;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserPhotoManager
{
    public const PUBLIC_PHOTOS_DIR = 'photos/';

    private ?string $savePath;
    private FileValidator $fileValidator;
    private FileNameGenerator $fileNameGenerator;
    private FileUploader $fileUploader;
    private DirectoryManager $directoryManager;

    public function __construct(
        FileValidator $fileValidator,
        FileNameGenerator $fileNameGenerator,
        FileUploader $fileUploader,
        DirectoryManager $directoryManager,
    ) {
        $this->fileValidator = $fileValidator;
        $this->fileNameGenerator = $fileNameGenerator;
        $this->fileUploader = $fileUploader;
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
    public function uploadUserPhotos(array $files): array
    {
        $photoDetails = [];
        $this->setSavePath();

        foreach ($files as $index => $file) {
            if ('avatar' === $file->getClientOriginalName()) {
                $photoDetails[] = $this->uploadAvatar($file);
                unset($files[$index]);
                break;
            }
        }

        $savedFiles = $this->fileUploader->uploadFiles($files, $this->savePath);
        foreach ($savedFiles as $savedFile) {
            $photoDetails[] = new PhotoDetails(
                $savedFile['name'],
                $savedFile['path']
            );
        }

        return $photoDetails;
    }

    /**
     * @throws IOExceptionInterface
     */
    public function setSavePath(?string $savePath = null): void
    {
        $this->savePath = $savePath;
        if (null === $this->savePath) {
            $uniqueDirName = uniqid();
            $this->savePath = self::PUBLIC_PHOTOS_DIR.$uniqueDirName;
        }

        $this->directoryManager->ensureDirectoryExists($this->savePath);
    }

    public function getSavePath(): ?string
    {
        return $this->savePath;
    }

    /**
     * @throws ValidationException
     * @throws IOExceptionInterface
     */
    private function uploadAvatar(UploadedFile $avatar): PhotoDetails
    {
        $this->fileValidator->validate($avatar);
        $savedAvatarFile = $this->fileUploader->uploadFile($avatar, $this->savePath);

        return new PhotoDetails(
            $savedAvatarFile['name'],
            $savedAvatarFile['path']
        );
    }
}
