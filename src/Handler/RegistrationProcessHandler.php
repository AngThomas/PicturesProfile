<?php

namespace App\Handler;

use App\DTO\UserDTO;
use App\Exception\ValidationException;
use App\Model\RegistrationStatus;
use App\Service\User\RegistrationService;
use App\Service\User\UserManager;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class RegistrationProcessHandler
{
    private RegistrationService $registrationService;

    public function __construct(
        RegistrationService $registrationService,
    ) {
        $this->registrationService = $registrationService;
    }

    /**
     * @throws ValidationException
     * @throws IOExceptionInterface
     */
    public function handle(UserDTO $userDTO): RegistrationStatus
    {
        //        try {
        $result = $this->registrationService->register($userDTO);

        return new RegistrationStatus(
            $result,
            RegistrationStatus::SUCCESS
        );
        //        } catch (\Exception $e) {
        //            return new RegistrationStatus(
        //                false,
        //                RegistrationStatus::FAIL
        //            );
        //        }
    }
}
