<?php

namespace App\Service;

use App\Service\User\UserManager;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\InvalidTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class TokenManager
{
    private JWTTokenManagerInterface $jwtManager;
    private UserManager $userManager;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        UserManager $userManager
    )
    {
        $this->jwtManager = $jwtManager;
        $this->userManager = $userManager;
    }

    public function issueToken(UserInterface $user): string
    {
        return $this->jwtManager->create($user);
    }

    public function checkToken(array $tokenDetails): bool
    {
        if (empty($tokenDetails) || !isset($tokenDetails['exp'])) {
            return false;
        }

        $currentTime = time();
        if ($tokenDetails['exp'] < $currentTime) {
            return false;
        }
        return false;
    }


    /**
     * @throws JWTDecodeFailureException
     */
    public function getTokenDetails(TokenInterface|string $token): array
    {
        if('string' === gettype($token)) {
            $token = new PreAuthenticationJWTUserToken($token);
        }

        $decodedToken = $this->jwtManager->decode($token);
        if (!$decodedToken) {
            throw new JWTDecodeFailureException('Failed to decode token', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $decodedToken;
    }


    public function refreshToken(array $tokenDetails): string
    {
        $user = $this->userManager->getUserByEmail($tokenDetails['email']);

        return $this->issueToken($user);
    }

}