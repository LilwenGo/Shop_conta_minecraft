<?php 
namespace App\Controller;

use App\Entity\Item;
use App\Service\ItemService;
use App\Service\MembreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/items")]
class ItemController extends AbstractController {
    #[Route('/', 'items', methods: 'GET')]
    public function index(): JsonResponse {
        $team = $this->getUser()->getTeam();
        if(!$team) {
            return new JsonResponse(['error' => 'Membre hasn\'t a team'], 403);
        }
        $items = [];
        foreach($team->getItems() as $item) {
            array_push($items, $item->toJson());
        }
        return new JsonResponse(['success' => true, 'items' => $items], 200);
    }

    #[Route('/', 'create_item', methods: 'POST')]
    public function create(ItemService $service, MembreService $mService, Request $request): JsonResponse {
        if(!$this->isGranted('ROLE_MODERATOR') && !$this->isGranted('ROLE_MANAGER')) {
            return new JsonResponse(['success' => false, 'error' => 'Unauthorized'], 403);
        }
        $data = json_decode($request->getContent(), true);
        if (!isset($data['name'], $data['price'], $data['manager'])) {
            return new JsonResponse(['success' => false, 'error' => 'Missing fields'], 400);
        }
        $manager = $mService->getByName($data['manager']);
        if(!$manager || $manager->getTeam()->getId() !== $this->getUser()->getTeam()->getId()) {
            return new JsonResponse(['success' => false, 'error' => 'Invalid manager'], 400);
        }
        $item = new Item();
        $item->setName($data['name']);
        $item->setPrice($data['price']);
        $item->setTeam($this->getUser()->getTeam());
        $item->setManager($manager);
        try {
            $service->save($item);
            return new JsonResponse([
                'success' => true,
                'item' => $item->toJson() 
            ]);
        } catch(\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Error while saving entity: '.$e->getMessage()
            ], 500);
        }
    }

    #[Route('/{id}', 'update_item', methods: 'PUT')]
    public function update(ItemService $service, MembreService $mService, int $id, Request $request): JsonResponse {
        if(!$this->isGranted('ROLE_MODERATOR') && !$this->isGranted('ROLE_MANAGER')) {
            return new JsonResponse(['success' => false, 'error' => 'Unauthorized'], 403);
        }
        $data = json_decode($request->getContent(), true);
        if (!isset($data['name'], $data['price'], $data['manager'])) {
            return new JsonResponse(['success' => false, 'error' => 'Missing fields'], 400);
        }
        $item = $service->getById($id);
        if(!$item || $item->getTeam()->getId() !== $this->getUser()->getTeam()->getId()) {
            return new JsonResponse(['success' => false, 'error' => 'You cannot modify this item'], 403);
        }
        if($item->getManager()->getUserIdentifier() !== $data['name']) {
            $manager = $mService->getByName($data['manager']);
            if(!$manager || $manager->getTeam()->getId() !== $this->getUser()->getTeam()->getId()) {
                return new JsonResponse(['success' => false, 'error' => 'Invalid manager'], 400);
            } else {
                $item->setManager($manager);
            }
        }
        $item->setName($data['name']);
        $item->setPrice($data['price']);
        try {
            $service->save($item);
            return new JsonResponse([
                'success' => true,
                'item' => $item->toJson() 
            ]);
        } catch(\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Error while saving entity: '.$e->getMessage()
            ], 500);
        }
    }

    #[Route('/{id}', 'delete_item', methods: 'DELETE')]
    public function delete(ItemService $service, int $id): JsonResponse {
        $item = $service->getById($id);
        if(!$item) {
            return new JsonResponse(['error' => 'Item to delete not found'], 404);
        }
        if(!$this->getUser()->getTeam()->getItems()->contains($item)) {
            return new JsonResponse(['success' => false, 'error' => 'Unauthorized'], 403);
        }
        try {
            $service->delete($item);
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