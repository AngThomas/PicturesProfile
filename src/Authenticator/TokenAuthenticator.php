<?php

namespace App\Authenticator;

use App\Service\TokenManager;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class TokenAuthenticator extends AbstractAuthenticator
{

    private TokenManager $tokenManager;

    public function __construct(
        TokenManager $tokenManager
    )
    {
        $this->tokenManager = $tokenManager;
    }
    public function supports(Request $request): bool
    {
        return $request->headers->has('Authorization');
    }

    /**
     * @throws JWTDecodeFailureException
     */
    public function authenticate(Request $request)
    {
        $authHeader = $request->headers->get('Authorization');
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            throw new AuthenticationException('Invalid token');
        }

        $tokenDetails = $this->tokenManager->getTokenDetails($matches[1]);
        if (!$this->tokenManager->checkToken($tokenDetails))
        {
            throw new AuthenticationException('Invalid token');
        }

        return new PreAuthenticationJWTUserToken($matches[1]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $tokenDetails = $this->tokenManager->getTokenDetails($token);
        $newToken = $this->tokenManager->refreshToken($tokenDetails);
        $response = $request->attributes->get('_response');
        if (!$response instanceof Response) {
            return new Response(json_encode(['error' =>'Not Found']), Response::HTTP_NOT_FOUND);
        }
        return $response;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new Response (json_encode(['error' =>'Unathorized']), Response::HTTP_UNAUTHORIZED);
    }
}