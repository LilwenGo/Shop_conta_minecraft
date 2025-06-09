<?php 
namespace App\Controller;

use App\Entity\Membre;
use App\Entity\Team;
use App\Service\MembreService;
use App\Service\RoleService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api")]
class MembreController extends AbstractController {
    #[Route('/register', 'register', methods: 'POST')]
    public function register(
        Request $request,
        MembreService $service,
        RoleService $rService,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['username'], $data['password'], $data['team'])) {
            return new JsonResponse(['error' => 'Missing fields'], 400);
        }
        $team = new Team();
        $team->setName($data['team']);
        $user = new Membre();
        $user->setName($data['username']);
        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
        $user->addRole($rService->getByLibelle("Responsable"));
        $team->setOwner($user);
        $user->setTeam($team);
        try {
            $service->save($user);
            $response = new JsonResponse([
                'success' => true,
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getUserIdentifier(),
                    'roles' => $user->getRoleLibelles(),
                ]
            ]);
        } catch(\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Registration failed',
                'message' => $e->getMessage()
            ], 500);
        }
        $token = $jwtManager->create($user);
        $response->headers->setCookie(
            Cookie::create('BEARER')->withValue($token)->withHttpOnly(true)->withSecure(true)->withSameSite('Strict')->withPath('/api')
        );
        return $response;
    }

    #[Route('/logout', 'logout', methods: 'GET')]
    public function logout(): JsonResponse {
        // On crée un cookie vide avec expiration immédiate pour le supprimer côté client
        $cookie = Cookie::create('BEARER')
            ->withValue('')
            ->withExpires(0)
            ->withHttpOnly(true)
            ->withSecure(true)
            ->withSameSite('Strict')
            ->withPath('/api'); // même path que celui utilisé à la connexion

        $response = new JsonResponse(['success' => true, 'message' => 'Logged out']);
        $response->headers->setCookie($cookie);
        
        return $response;
    }

    #[Route('/membres', 'membres', methods: 'GET')]
    public function index(MembreService $service): JsonResponse {
        $users = [];
        foreach($service->getAll() as $user) {
            array_push($users, [
                'id' => $user->getId(),
                'name' => $user->getUserIdentifier(),
                'roles' => $user->getRoleLibelles(),
            ]);
        }
        return new JsonResponse();
    }
}