<?php

namespace App\Handler;

use App\DTO\CredentialsDTO;
use App\Service\TokenService;
use App\Service\User\LoginService;

class LoginProcessHandler
{

    private LoginService $loginService;
    private TokenService $tokenService;

    public function __construct(
        LoginService $loginService,
        TokenService $tokenService
    )
    {
        $this->loginService = $loginService;
        $this->tokenService = $tokenService;
    }
    public function handle(CredentialsDTO $credentialsDTO)
    {
        $user = $this->loginService->login($credentialsDTO);
        if (isset($user)) {
            $this->tokenService->issueToken($user);
        }

    }
}