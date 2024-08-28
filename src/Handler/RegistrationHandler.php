<?php

namespace App\Handler;

use App\Service\FileUploadService;
use App\Service\User\RegistrationService;

class RegistrationHandler
{
    private RegistrationService $registrationService;
    private FileUploadService $fileUploadService;
    public function __construct(
        RegistrationService $registrationService,
        FileUploadService $fileUploadService
    )
    {
        $this->registrationService = $registrationService;
        $this->fileUploadService = $fileUploadService;
    }

    public function handle()
    {
        $this->registrationService->register('', '');
        $this->fileUploadService->uploadFiles();
    }
}