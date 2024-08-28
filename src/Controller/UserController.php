<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/users')]
class UserController extends AbstractController
{
    #[Route('/register', name: 'app_user_register')]
    public function register(): JsonResponse
    {

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
