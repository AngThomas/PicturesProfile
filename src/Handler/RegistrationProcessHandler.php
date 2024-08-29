<?php

namespace App\Handler;

use App\CustomExceptions\ValidationException;
use App\DTO\UserDTO;
use App\Service\FileProcessingService;
use App\Service\User\RegistrationService;
use App\Service\User\UserManager;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\Request;

class RegistrationProcessHandler
{
    private UserManager $userManager;
    private RegistrationService $registrationService;
    private FileProcessingService $fileUploadService;
    public function __construct(
        UserManager $userManager,
        RegistrationService $registrationService,
        FileProcessingService $fileUploadService
    )
    {
        $this->userManager = $userManager;
        $this->registrationService = $registrationService;
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * @throws ValidationException
     * @throws IOExceptionInterface
     */
    public function handle(UserDTO $userDTO)
    {
        $this->fileUploadService->uploadFiles($userDTO->getFiles());
        $userEntity = $this->userManager->makeNewUser($userDTO);
        $this->registrationService->register($userEntity);
    }
}