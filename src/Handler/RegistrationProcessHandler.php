<?php

namespace App\Handler;


use App\DTO\UserDTO;
use App\Exception\ValidationException;
use App\Model\RegistrationStatus;
use App\Service\FileProcessingService;
use App\Service\User\RegistrationService;
use App\Service\User\UserManager;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;


class RegistrationProcessHandler
{
    private UserManager $userManager;
    private RegistrationService $registrationService;
    private FileProcessingService $fileProcessingService;
    public function __construct(
        UserManager $userManager,
        RegistrationService $registrationService,
        FileProcessingService $fileProcessingService
    )
    {
        $this->userManager = $userManager;
        $this->registrationService = $registrationService;
        $this->fileProcessingService = $fileProcessingService;
    }

    /**
     * @throws ValidationException
     * @throws IOExceptionInterface
     */
    public function handle(UserDTO $userDTO): RegistrationStatus
    {
        try {
//            $this->fileProcessingService->uploadFiles($userDTO->getFiles());
            $userEntity = $this->userManager->makeNewUser($userDTO);
            $result = $this->registrationService->register($userEntity);

            return new RegistrationStatus(
                $result,
                RegistrationStatus::SUCCESS
            );
        } catch (\Exception $e) {
            return new RegistrationStatus(
                false,
                RegistrationStatus::FAIL
            );
        }
    }
}