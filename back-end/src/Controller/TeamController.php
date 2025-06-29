<?php
namespace App\Controller;

use App\Entity\Membre;
use App\Entity\Team;
use App\Service\ItemService;
use App\Service\MembreService;
use App\Service\RoleService;
use App\Service\TeamService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("/api/teams")]
class TeamController extends AbstractController {
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/', 'teams', methods: 'GET')]
    public function index(TeamService $service): JsonResponse {
        $teams = [];
        foreach($service->getAll() as $team) {
            array_push($teams, [
                'id' => $team->getId(),
                'libelle' => $team->getLibelle()
            ]);
        }
        return new JsonResponse($teams, 200);
    }

    #[Route('/my_team', 'my_team', methods: 'GET')]
    public function getFromAuthentication(): JsonResponse {
        $user = $this->getUser();
        if($user instanceof Membre) {
            $team = $user->getTeam();
            return new JsonResponse(['success' => true, 'team' => $team ? $team->toJson() : null]);
        } else {
            return new JsonResponse(['success' => false, 'error' => "Access denied"], 403);
        }
    }

    #[Route('/', 'create_team', methods: 'POST')]
    public function create(
        Request $request,
        TeamService $service,
        RoleService $rService,
        JWTTokenManagerInterface $jwtManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['team'])) {
            return new JsonResponse(['success' => false, 'error' => 'Missing fields'], 400);
        }
        $user = $this->getUser();
        if($user->getTeam()) {
            return new JsonResponse(['success' => false, 'error' => 'You already have a team'], 403);
        }
        $team = new Team();
        $team->setName($data['team']);
        $role = $rService->getByLibelle("Responsable");
        $user->addRole($role);
        $role->addMembre($user);
        $team->setOwner($user);
        $user->setTeam($team);
        try {
            $service->save($team);
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
                'error' => 'Error: '.$e->getMessage()
            ], 500);
        }
        $token = $jwtManager->create($user);
        $response->headers->setCookie(
            Cookie::create('BEARER')
                ->withValue($token)
                ->withHttpOnly(true)
                ->withSecure(true)
                ->withSameSite('Strict')
                ->withPath('/api')
        );
        return $response;
    }

    #[Route('/hire', 'add_membre', methods: 'POST')]
    public function addMembre(Request $request, MembreService $service, RoleService $rService): JsonResponse {
        if(!$this->isGranted('ROLE_MODERATOR') && !$this->isGranted('ROLE_MANAGER')) {
            return new JsonResponse(['success' => false, 'error' => 'Unauthorized'], 403);
        }
        $data = json_decode($request->getContent(), true);
        if (!isset($data['membreId'])) {
            return new JsonResponse(['success' => false, 'error' => 'Missing fields'], 400);
        }
        $user = $this->getUser();
        $team = $user->getTeam();
        if(!$team) {
            return new JsonResponse(['success' => false, 'error' => 'Your team was not found'], 404);
        }
        $membre = $service->getById($data['membreId']);
        if(!$membre) {
            return new JsonResponse(['success' => false, 'error' => 'Membre to add not found'], 404);
        }
        if($membre->getTeam() !== null || $membre->getOwnedTeam() !== null) {
            return new JsonResponse(['success' => false, 'error' => 'This membre already have a team'], 403);
        }
        $role = $rService->getByLibelle("Membre");
        $membre->addRole($role);
        $role->addMembre($membre);
        $membre->setTeam($team);
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

    #[Route('/fire', 'remove_membre', methods: 'PUT')]
    public function removeMembre(Request $request, MembreService $service, ItemService $iService): JsonResponse {
        if(!$this->isGranted('ROLE_MODERATOR') && !$this->isGranted('ROLE_MANAGER')) {
            return new JsonResponse(['success' => false, 'error' => 'Unauthorized'], 403);
        }
        $data = json_decode($request->getContent(), true);
        if (!isset($data['membreId'])) {
            return new JsonResponse(['success' => false, 'error' => 'Missing fields'], 400);
        }
        $user = $this->getUser();
        $team = $user->getTeam();
        if(!$team) {
            return new JsonResponse(['success' => false, 'error' => 'Your team was not found'], 404);
        }
        $membre = $service->getById($data['membreId']);
        if(!$membre) {
            return new JsonResponse(['success' => false, 'error' => 'Membre to remove not found'], 404);
        }
        if(in_array('ROLE_MANAGER', $membre->getRoles())) {
            return new JsonResponse(['success' => false, 'error' => 'You cannot fire this membre because he is the team owner'], 403);
        }
        if($membre->getTeam() !== $team) {
            return new JsonResponse(['success' => false, 'error' => 'You cannot fire this membre because you aren\'t in his team'], 403);
        }
        $membre->setTeam(null);
        foreach($membre->getRawRoles()->toArray() as $rawRole) {
            $membre->getRawRoles()->removeElement($rawRole);
            $rawRole->getMembres()->removeElement($membre);
        }
        if($membre->getItems()->count() > 0) {
            $iService->transferItems($membre, $team->getOwner());
        }
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
}