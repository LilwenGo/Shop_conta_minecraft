<?php
namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Cookie;
use App\Entity\Membre;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): JsonResponse
    {
        $user = $token->getUser();

        if($user instanceof Membre) {
            // Génère le token JWT
            $jwt = $this->jwtManager->create($user);
    
            $response = new JsonResponse([
                'success' => true,
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getUserIdentifier(),
                    'roles' => $user->getRoleLibelles()
                ]
            ]);
            $response->headers->setCookie(
                Cookie::create('BEARER')->withValue($jwt)->withHttpOnly(true)->withSecure(true)->withSameSite('Strict')->withPath('/api')
            );
            return $response;
        } else {
            return new JsonResponse(['success' => false, 'error' => "Something went wrong"], 500);
        }
    }
}