<?php 
namespace App\Controller;

use App\Entity\Item;
use App\Entity\Transaction;
use App\Service\ItemService;
use App\Service\MembreService;
use App\Service\TransactionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/transactions')]
class TransactionController extends AbstractController {
    #[Route('/', 'transactions', methods: 'GET')]
    public function index(TransactionService $service): JsonResponse {
        $team = $this->getUser()->getTeam();
        if(!$team) {
            return new JsonResponse(['error' => 'Membre hasn\'t a team'], 403);
        }
        $transactions = [];
        foreach($service->getByTeam($team) as $transaction) {
            array_push($transactions, $transaction->toJson());
        }
        return new JsonResponse(['success' => true, 'transactions' => $transactions], 200);
    }

    #[Route('/', 'create_transaction', methods: 'POST')]
    public function create(TransactionService $service, ItemService $iService, Request $request): JsonResponse {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['item'], $data['qty'])) {
            return new JsonResponse(['success' => false, 'error' => 'Missing fields'], 400);
        }
        $item = $iService->getById($data['item']);
        if(!$item || $item->getTeam()->getId() !== $this->getUser()->getTeam()->getId()) {
            return new JsonResponse(['success' => false, 'error' => 'You cannot use this item'], 403);
        }
        $transaction = new Transaction();
        $transaction->setItem($item);
        $transaction->setMembre($this->getUser());
        $transaction->setSum($data['qty'] * $item->getPrice());
        $transaction->setRefundedSum(0);
        try {
            $service->save($transaction);
            return new JsonResponse([
                'success' => true,
                'transaction' => $transaction->toJson() 
            ]);
        } catch(\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Error while saving entity: '.$e->getMessage()
            ], 500);
        }
    }

    #[Route('/{itemId}', 'edit_transaction', methods: 'PUT')]
    public function update(TransactionService $service, Request $request, int $itemId): JsonResponse {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['refundedSum'])) {
            return new JsonResponse(['success' => false, 'error' => 'Missing fields'], 400);
        }
        $transaction = $service->getById($this->getUser()->getId(), $itemId);
        if(!$transaction || $transaction->getMembre()->getId() !== $this->getUser()->getId()) {
            return new JsonResponse(['success' => false, 'error' => 'You cannot modify this transaction'], 403);
        }
        $transaction->setRefundedSum($data['refundedSum']);
        try {
            $service->save($transaction);
            return new JsonResponse([
                'success' => true,
                'transaction' => $transaction->toJson() 
            ]);
        } catch(\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Error while saving entity: '.$e->getMessage()
            ], 500);
        }
    }

    #[Route('/{itemId}', 'delete_transaction', methods: 'DELETE')]
    public function delete(TransactionService $service, int $itemId): JsonResponse {
        $transaction = $service->getById($this->getUser()->getId(), $itemId);
        if(!$transaction) {
            return new JsonResponse(['error' => 'Item to delete not found'], 404);
        }
        if($this->getUser()->getUserIdentifier() !== $transaction->getItem()->getManager()->getUserIdentifier()) {
            return new JsonResponse(['success' => false, 'error' => 'Unauthorized'], 403);
        }
        try {
            $service->delete($transaction);
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