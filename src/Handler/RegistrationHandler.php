<?php

namespace App\Handler;

use App\DTO\UserDTO;
use App\Service\FileUploadService;
use App\Service\User\RegistrationService;
use App\Service\User\UserManager;
use Symfony\Component\HttpFoundation\Request;

class RegistrationHandler
{
    private UserManager $userManager;
    private RegistrationService $registrationService;
    private FileUploadService $fileUploadService;
    public function __construct(
        UserManager $userManager,
        RegistrationService $registrationService,
        FileUploadService $fileUploadService
    )
    {
        $this->userManager = $userManager;
        $this->registrationService = $registrationService;
        $this->fileUploadService = $fileUploadService;
    }

    public function handle(Request $request)
    {
        $userDTO = UserDTO::fromRequest($request);
        $userEntity = $this->userManager->makeNewUser($userDTO);
        $this->registrationService->register($userEntity);
        $this->fileUploadService->uploadFiles();
    }
}