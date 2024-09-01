<?php

namespace App\Service\User;

use App\DTO\UserDTO;
use App\Entity\Photo;
use App\Entity\User;
use App\Model\PhotoDetails;
use App\Model\UserDetails;
use App\Repository\UserRepository;
use App\Service\FileProcessingService;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\UserProvider\UserProviderFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserManager
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private FileProcessingService $fileProcessingService;
    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        FileProcessingService $fileProcessingService
    )
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->fileProcessingService = $fileProcessingService;
    }
    public function makeNewUser(UserDTO $userDTO): User
    {
        $photoEntities = [];
        if ($this->userRepository->findOneBy(['email' => $userDTO->getEmail()]))
        {
            throw new BadCredentialsException('User already exists');
        }


        $user = new User(
            $userDTO->getEmail(),
            $userDTO->getPassword(),
            $userDTO->getFirstName(),
            $userDTO->getLastName(),
            $userDTO->isActive(),
            $userDTO->getAvatar()
        );

        foreach ($userDTO->getPhotos() as $photo)
        {
            $photoEntities[] = new Photo(
                $photo->getName(),
                $photo->getUrl()
            );

        }

        $user->setPhotos($photoEntities);

        return $user;
    }

    public function saveNewUser(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function saveUserPhotos(UserDto $userDTO): void
    {
        $files = $userDTO->getFiles();

        if (empty($files)) {
            return;
        }

        foreach ($files as $index => $file) {
            if ('avatar' === $file->getClientOriginalName()) {
                $savePath = $this->saveUserAvatar($file, $userDTO);
                unset($files[$index]);
                break;
            }
        }
        $savePath = $this->fileProcessingService->setSavePath();

        $photos = $this->fileProcessingService->uploadFiles($files, $savePath);
        $userDTO->setPhotos($photos);
    }

    public function getUserDetails(User $user): UserDetails
    {
        $photos = $user->getPhotos();
        $modelPhotos = [];
        if (isset($photos)) {
            $modelPhotos = $this->convertToModels($photos->toArray());
        }
        return new UserDetails(
            $user->getEmail(),
            $user->getFullName(),
            $user->isActive(),
            $user->getAvatar(),
            $modelPhotos
        );
    }

    private function convertToModels(array $photos): array
    {
        $modelPhotos = [];
        foreach ($photos as $photo) {
            $modelPhotos[] = new PhotoDetails(
                $photo->getName(),
                $photo->getUrl()
            );
        }
        return $modelPhotos;
    }
    private function saveUserAvatar(UploadedFile $avatar, UserDTO $userDTO): string
    {
        $this->fileProcessingService->validateFile($avatar);
        $savePath = $this->fileProcessingService->setSavePath();
        $avatarPhoto = $this->fileProcessingService->saveFile($avatar, $savePath);
        $userDTO->setAvatar($avatarPhoto->getUrl());
        $userDTO->addPhoto($avatarPhoto);
        return $savePath;
    }
}