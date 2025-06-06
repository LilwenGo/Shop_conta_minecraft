<?php 
namespace App\Controller;

use App\Service\RoleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/roles")]
class RoleController extends AbstractController {
    #[Route('/', 'roles', methods: 'GET')]
    public function index(RoleService $service): JsonResponse {
        $roles = [];
        foreach($service->getAll() as $role) {
            array_push($roles, [
                'id' => $role->getId(),
                'libelle' => $role->getLibelle()
            ]);
        }
        return new JsonResponse($roles, 200);
    }
}