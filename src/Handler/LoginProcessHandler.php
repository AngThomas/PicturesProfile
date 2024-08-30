<?php

namespace App\Handler;

use App\DTO\CredentialsDTO;
use App\Service\TokenManager;
use App\Service\User\LoginService;

class LoginProcessHandler
{

    private LoginService $loginService;
    private TokenManager $tokenManager;

    public function __construct(
        LoginService $loginService,
        TokenManager $tokenManager
    )
    {
        $this->loginService = $loginService;
        $this->tokenService = $tokenManager;
    }
    public function handle(CredentialsDTO $credentialsDTO)
    {
        $user = $this->loginService->login($credentialsDTO);
        if (isset($user)) {
            $this->tokenService->issueToken($user);
        }

    }
}