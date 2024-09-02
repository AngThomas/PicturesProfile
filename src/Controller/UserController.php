<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Handler\RegistrationProcessHandler;
use App\Service\User\UserManager;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/users')]
class UserController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(
        SerializerInterface $serializer,
    ) {
        $this->serializer = $serializer;
    }

    #[Route('/register', name: 'app_user_register', methods: ['POST'])]
    public function register(Request $request, RegistrationProcessHandler $registrationProcessHandler): JsonResponse
    {
        $registrationStatus = $registrationProcessHandler->handle(UserDTO::fromRequest($request));

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($registrationStatus, 'json', SerializationContext::create()),
            $registrationStatus->isSuccess() ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    #[Route('/me', name: 'app_user_details', methods: ['GET'])]
    public function userDetails(UserManager $userManager): JsonResponse
    {
        $userModel = $userManager->getUserDetails($this->getUser());

        return JsonResponse::fromJsonString(
            $this->serializer->serialize($userModel, 'json', SerializationContext::create()),
            Response::HTTP_OK
        );
    }
}
