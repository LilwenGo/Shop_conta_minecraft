<?php
namespace Project\Controllers;
use Project\Models\ItemManager;
use Project\Models\MembreManager;
use Project\Models\SoldManager;

/**
 * Class SoldController
 */
class SoldController extends Controller {
    /**
     * MembreManager
     */
    private MembreManager $mManager;

    /**
     * MembreManager
     */
    private ItemManager $iManager;

    /**
     * Init the managers and the validator
     */
    public function __construct() {
        $this->manager = new SoldManager();
        $this->mManager = new MembreManager();
        $this->iManager = new ItemManager();
        parent::__construct();
    }

    /**
     * Render the solds edition 
     */
    public function index(string $filter, int $id): void {
        if(isset($_SESSION['team'])) {
            $solds = match($filter) {
                'item' => $this->manager->getFromItem($id),
                'membre' => $this->manager->getFromMembre($id),
                default => []
            };
            $membres = $this->mManager->getFromTeam($_SESSION['team']['id']);
            $items = $this->iManager->getFromTeam($_SESSION['team']['id']);
            Controller::render('Sold/index', ['solds' => $solds, 'items' => $items, 'membres' => $membres]);
        } else {
            header('Location: /team/login');
        }
    }

    /**
     * Store the new sold if valid
     */
    public function store(): void {
        if(isset($_SESSION['team'])) {
            $this->validator->validate([
                'item' => ['required', 'numeric'],
                'membre' => ['required', 'numeric'],
                'quantity' => ['required', 'numeric'],
                'refunded' => ['required', 'numeric']
            ]);

            if(!$this->validator->errors()) {
                $item = $this->iManager->find($_POST['item']);
                $membre = $this->mManager->find($_POST['membre']);
                if($item && $membre) {
                    $this->manager->create($item->getId(), $membre->getId(), $_POST['quantity'], $_POST['refunded']);
                    echo json_encode(['success' => true, 'data' => ['id_item' => $item->getId(), 'id_membre' => $membre->getId(), 'item' => $item->getLibelle(), 'membre' => $membre->getName()]]);
                } else {
                    echo json_encode(['success' => false, 'errors' => ['message' => 'Une erreur est survenue lors de la séléction de l\'item ou du membre!']]);
                }
            } else {
                echo json_encode(['success' => false, 'errors' => $_SESSION['error']]);
                unset($_SESSION['error']);
            }
        } else {
            header('Location: /team/login');
        }
    }

    /**
     * Update the sold with matching id if valid
     */
    public function update(string $id): void {
        if(isset($_SESSION['team'])) {
            $this->validator->validate([
                'quantity' => ['required', 'numeric'],
                'refunded' => ['required', 'numeric']
            ]);

            if(!$this->validator->errors()) {
                $ids = explode(',', $id);
                $sold = $this->manager->find($ids[0], $ids[1]);
                if($sold) {
                    $success = $this->manager->update($ids[0], $ids[1], $_POST['quantity'], $_POST['refunded']);
                    if($success !== 0) {
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'errors' => ['message' => 'Une erreur est survenue !']]);
                    }
                } else {
                    echo json_encode(['success' => false, 'errors' => ['message' => 'La vente n\'a pas été trouvé !']]);
                }
            } else {
                echo json_encode(['success' => false, 'errors' => $_SESSION['error']]);
                unset($_SESSION['error']);
            }
        } else {
            header('Location: /team/login');
        }
    }

    /**
     * Delete the membre with matching id
     */
    public function delete(string $id): void {
        if(isset($_SESSION['team'])) {
            $ids = explode(',', $id);
            $success = $this->manager->delete($ids[0], $ids[1]);
            if($success !== 0) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'errors' => ['message' => 'Une erreur est survenue !']]);
            }
        } else {
            header('Location: /team/login');
        }
    }
}