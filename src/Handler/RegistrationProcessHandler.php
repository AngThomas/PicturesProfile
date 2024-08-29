<?php

namespace App\Handler;

use App\DTO\UserDTO;
use App\Service\FileProcessingService;
use App\Service\User\RegistrationService;
use App\Service\User\UserManager;
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

    public function handle(UserDTO $userDTO)
    {
        $userEntity = $this->userManager->makeNewUser($userDTO);
        $this->fileUploadService->uploadFiles();
        $this->registrationService->register($userEntity);
    }
}