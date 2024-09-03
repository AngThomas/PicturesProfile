<?php

namespace App\Service\User;

use App\DTO\JmsSerializable\PhotoDetailsDTO;
use App\DTO\UserDTO;
use App\Exception\ValidationException;
use App\Service\DirectoryManager;
use App\Service\File\FileUploader;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;

class UserPhotoManager
{
    public const PUBLIC_PHOTOS_DIR = 'photos/';
    public const DEFAULT_AVATAR_PATH = self::PUBLIC_PHOTOS_DIR.'default/avatar.jpg';

    private ?string $savePath;
    private string $baseUrl;
    private FileUploader $fileUploader;
    private DirectoryManager $directoryManager;

    private RequestStack $stack;

    public function __construct(
        FileUploader $fileUploader,
        DirectoryManager $directoryManager,
        RequestStack $stack,
    ) {
        $this->fileUploader = $fileUploader;
        $this->directoryManager = $directoryManager;
        $this->stack = $stack;
    }

    /**
     * @throws IOExceptionInterface
     * @throws ValidationException
     */
    public function uploadUserPhotos(UserDTO $userDto): void
    {
        $photoDetails = [];
        $files = $userDto->getFiles();
        $this->baseUrl = $this->stack->getCurrentRequest()->getSchemeAndHttpHost();
        $userDto->setAvatar($this->baseUrl.'/'.self::DEFAULT_AVATAR_PATH);
        $this->setSavePath();

        foreach ($files as $index => $file) {
            if ('avatar' === $file->getClientOriginalName()) {
                $avatarPhoto = $this->uploadAvatar($file);
                $photoDetails[] = $avatarPhoto;
                $userDto->setAvatar($avatarPhoto->getUrl());
                unset($files[$index]);
                break;
            }
        }

        $files = $this->fileUploader->uploadFiles($files, $this->savePath);
        foreach ($files as $file) {
            $photoDetails[] = new PhotoDetailsDTO(
                $file->getFilename(),
                $this->baseUrl.'/'.$file->getPathname()
            );
        }

        $userDto->setPhotos($photoDetails);
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
     * @throws \InvalidArgumentException
     */
    private function uploadAvatar(UploadedFile $avatar): PhotoDetailsDTO
    {
        $file = $this->fileUploader->uploadFile($avatar, $this->savePath);

        return new PhotoDetailsDTO(
            $file->getFilename(),
            $this->baseUrl.'/'.$file->getPathname()
        );
    }
}
