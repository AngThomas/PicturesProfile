<?php

namespace App\Handler;

use App\DTO\UserDTO;
use App\Exception\ValidationException;
use App\Interface\RegistrationInterface;
use App\Model\RegistrationStatus;
use App\Service\User\RegistrationService;
use App\Service\User\UserManager;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class RegistrationProcessHandler
{
    private RegistrationInterface $registrationService;

    public function __construct(
        RegistrationInterface $registrationService,
    ) {
        $this->registrationService = $registrationService;
    }

    /**
     * @throws ValidationException
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
