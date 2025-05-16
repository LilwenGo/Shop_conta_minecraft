<?php
namespace Project\Controllers;
use Project\Models\CategoryManager;
use Project\Models\ItemManager;
use Project\Models\MembreManager;
use Project\Models\SoldManager;
use Project\Models\TeamManager;
use \Exception;

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
     * CategoryManager
     */
    private CategoryManager $cManager;
    
    /**
     * TeamManager
     */
    private TeamManager $tManager;

    /**
     * Init the managers and the validator
     */
    public function __construct() {
        $this->manager = new SoldManager();
        $this->mManager = new MembreManager();
        $this->iManager = new ItemManager();
        $this->cManager = new CategoryManager();
        $this->tManager = new TeamManager();
        parent::__construct();
    }

    /**
     * Render the solds edition 
     */
    public function index(): void {
        if(isset($_SESSION['team'])) {
            $team = $this->tManager->find($_SESSION['team']['id']);
            if($team) {
                $membres = $this->mManager->getFromTeam($_SESSION['team']['id']);
                $items = $this->iManager->getFromTeam($_SESSION['team']['id']);
                $solds = $this->manager->getFromTeam($_SESSION['team']['id']);
                Controller::render('Sold/index', ['solds' => $solds, 'items' => $items, 'membres' => $membres]);
            } else {
                Controller::render('error', ['code' => 404, 'message' => 'Impossible de trouver la vente !']);
            }
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
                    try {
                        $this->manager->create($item->getId(), $membre->getId(), $_POST['quantity'], $_POST['refunded']);
                        echo json_encode(['success' => true, 'data' => ['id_item' => $item->getId(), 'id_membre' => $membre->getId(), 'item' => $item->getLibelle(), 'membre' => $membre->getName()]]);
                    } catch (Exception $e) {
                        echo json_encode(['success' => false, 'errors' => ['message' => $e->getMessage()]]);
                    }
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