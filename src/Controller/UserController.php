<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Handler\RegistrationProcessHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/users')]
class UserController extends AbstractController
{
    private RegistrationProcessHandler $registrationProcessHandler;
    public function __construct(
        RegistrationProcessHandler $registrationProcessHandler
    )
    {
        $this->registrationProcessHandler = $registrationProcessHandler;
    }

    #[Route('/register', name: 'app_user_register')]
    public function register(Request $request): JsonResponse
    {
        $this->registrationProcessHandler->handle(UserDTO::fromRequest($request));

    }

    #[Route('/login', name: 'app_user_login')]
    public function login(): JsonResponse
    {

    }

    #[Route('/me', name: 'app_user_details')]
    public function userDetails(): JsonResponse
    {

    }
}
