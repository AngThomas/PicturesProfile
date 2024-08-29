<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Handler\RegistrationProcessHandler;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api/users')]
class UserController extends AbstractController
{
    private RegistrationProcessHandler $registrationProcessHandler;
    private Serializer $serializer;
    public function __construct(
        RegistrationProcessHandler $registrationProcessHandler,
        Serializer $serializer
    )
    {
        $this->registrationProcessHandler = $registrationProcessHandler;
        $this->serializer = $serializer;
    }

    #[Route('/register', name: 'app_user_register')]
    public function register(Request $request): JsonResponse
    {
        $registrationStatus = $this->registrationProcessHandler->handle(UserDTO::fromRequest($request));
        return new JsonResponse(
            $this->serializer->serialize($registrationStatus, 'json', SerializationContext::create()),
            $registrationStatus->isSuccess() ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR
        );
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
