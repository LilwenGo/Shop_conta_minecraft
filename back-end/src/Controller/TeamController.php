<?php
namespace App\Controller;

use App\Entity\Membre;
use App\Service\TeamService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            return new JsonResponse(['success' => true, 'team' => $user->getTeam()->toJson()]);
        } else {
            return new JsonResponse(['success' => false, 'error' => "Access denied"], 403);
        }
    }
}