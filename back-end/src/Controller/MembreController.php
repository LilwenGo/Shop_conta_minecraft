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
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("/api")]
class MembreController extends AbstractController {
    #[Route('/register', 'register', methods: 'POST')]
    public function register(
        Request $request,
        MembreService $service,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['username'], $data['password'])) {
            return new JsonResponse(['success' => false, 'error' => 'Missing fields'], 400);
        }
        $user = new Membre();
        $user->setName($data['username']);
        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
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
                'error' => 'Registration failed: '.$e->getMessage()
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

    #[IsGranted('ROLE_ADMIN')]
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

    #[Route('/membres/orphans', 'orphans', methods: 'GET')]
    public function getOrphan(MembreService $service): JsonResponse {
        if(!$this->isGranted('ROLE_MODERATOR') && !$this->isGranted('ROLE_MANAGER')) {
            return new JsonResponse(['success' => false, 'error' => 'Unauthorized'], 403);
        }
        $users = [];
        foreach($service->getOrphans() as $user) {
            array_push($users, [
                'id' => $user->getId(),
                'name' => $user->getUserIdentifier(),
                'roles' => $user->getRoleLibelles(),
            ]);
        }
        return new JsonResponse(['success' => true, 'orphans' => $users], 200);
    }
/* 
    #[Route('/membres/reset-pass', 'reset_pass', methods: 'Get')]
    public function resetPassword(MembreService $service, UserPasswordHasherInterface $passwordHasher): JsonResponse {
        $membre = $service->getByName('LilwenGo');
        $membre->setPassword($passwordHasher->hashPassword($membre, 'motdepasse'));
        $service->save($membre);
        return new JsonResponse($membre);
    } */

    #[Route('/profile', 'edit_profile', methods: 'PUT')]
    public function update(Request $request, MembreService $service, UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtManager): JsonResponse {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['name'], $data['password'])) {
            return new JsonResponse(['success' => false, 'error' => 'Missing fields'], 400);
        }
        $membre = $service->getById($this->getUser()->getId());
        //Check if user exists
        if(!$membre) {
            return new JsonResponse(['error' => 'Membre to update not found'], 404);
        }
        if(!$passwordHasher->isPasswordValid($membre, $data['password'])) {
            return new JsonResponse(['error' => 'Incorrect password'], 403);
        }
        $membre->setName($data['name']);
        if(isset($data['newPassword']) && strlen(trim($data['newPassword'])) > 0) {
            $membre->setPassword($passwordHasher->hashPassword($membre, $data['newPassword']));
        }
        try {
            $service->save($membre);
            $response = new JsonResponse([
                'success' => true,
                'user' => [
                    'id' => $membre->getId(),
                    'name' => $membre->getUserIdentifier(),
                    'roles' => $membre->getRoleLibelles(),
                ]
            ]);
            $token = $jwtManager->create($membre);
            $response->headers->setCookie(
                Cookie::create('BEARER')
                    ->withValue($token)
                    ->withHttpOnly(true)
                    ->withSecure(true)
                    ->withSameSite('Strict')
                    ->withPath('/api')
            );
            return $response;
        } catch(\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Error while saving entity: '.$e->getMessage()
            ], 500);
        }
    }

    #[Route('/membres/{id}', 'edit_membre', methods: 'PUT')]
    public function updateRoles(Request $request, MembreService $service, RoleService $rService, string $id): JsonResponse {
        //Check if user is a team manager
        if(!$this->isGranted('ROLE_MANAGER')) {
            return new JsonResponse(['success' => false, 'error' => 'Unauthorized'], 403);
        }
        $data = json_decode($request->getContent(), true);
        if (!isset($data['role'])) {
            return new JsonResponse(['success' => false, 'error' => 'Missing fields'], 400);
        }
        $membre = $service->getById($id);
        //Check if user exists
        if(!$membre) {
            return new JsonResponse(['error' => 'Membre to update not found'], 404);
        }
        //Check if user has the rights on this member
        if($membre->getTeam()->getId() !== $this->getUser()->getTeam()->getId()) {
            return new JsonResponse(['success' => false, 'error' => 'Unauthorized'], 403);
        }
        $role = $rService->getByLibelle($data["role"]);
        //Check if role exists
        if(!$role) {
            return new JsonResponse(['error' => 'Invalid field "role"'], 400);
        }
        foreach($membre->getRawRoles()->toArray() as $rawRole) {
            if($role->getName() !== 'ROLE_ADMIN' && $role->getName() !== 'ROLE_MANAGER') {
                $membre->getRawRoles()->removeElement($rawRole);
                $rawRole->getMembres()->removeElement($membre);
            }
        }
        $membre->addRole($role);
        $role->addMembre($membre);
        try {
            $service->save($membre);
            return new JsonResponse([
                'success' => true,
                'membre' => [
                    'id' => $membre->getId(),
                    'name' => $membre->getUserIdentifier(),
                    'roles' => $membre->getRoleLibelles(),
                ]
            ]);
        } catch(\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Error while saving entity: '.$e->getMessage()
            ], 500);
        }
    }

    #[Route('/membres/{id}', 'delete_membre', methods: 'DELETE')]
    public function delete(MembreService $service, string $id): JsonResponse {
        $membre = $service->getById($id);
        if(!$membre) {
            return new JsonResponse(['error' => 'Membre to delete not found'], 404);
        }
        if($membre->getId() !== $this->getUser()->getId() || $this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['success' => false, 'error' => 'Unauthorized'], 403);
        }
        try {
            $service->delete($membre);
            return new JsonResponse([
                'success' => true
            ]);
        } catch(\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Error while deleting entity: '.$e->getMessage()
            ], 500);
        }
    }
}