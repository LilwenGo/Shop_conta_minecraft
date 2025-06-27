<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\JsonResponse;

class JwtCookieAuthenticator extends AbstractAuthenticator
{
    private JWTTokenManagerInterface $jwtManager;
    private UserProviderInterface $userProvider;

    public function __construct(JWTTokenManagerInterface $jwtManager, UserProviderInterface $userProvider)
    {
        $this->jwtManager = $jwtManager;
        $this->userProvider = $userProvider;
    }

    public function supports(Request $request): ?bool
    {
        return $request->cookies->has('BEARER');
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $token = $request->cookies->get('BEARER');

        try {
            $payload = $this->jwtManager->parse($token);
            $username = $payload['username'] ?? null;

            if (!$username) {
                throw new AuthenticationException('Invalid JWT payload');
            }

            return new SelfValidatingPassport(new UserBadge($username, function ($userIdentifier) {
                return $this->userProvider->loadUserByIdentifier($userIdentifier);
            }));
        } catch (\Exception $e) {
            throw new AuthenticationException('Invalid JWT token');
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        $token = $request->cookies->get('BEARER');
        $response = new JsonResponse(['error' => 'Authentication failed: '. $exception], 401);
        if($token) {
            $response->headers->setCookie(Cookie::create('BEARER')
            ->withValue('')
            ->withExpires(0)
            ->withHttpOnly(true)
            ->withSecure(true)
            ->withSameSite('Strict')
            ->withPath('/api'));
        }
        return $response;
    }

    public function onAuthenticationSuccess(Request $request, $token, string $firewallName): ?JsonResponse
    {
        return null; // let the request continue
    }
}
