<?php
namespace Project\Controllers;
use Project\Models\CategoryManager;
use Project\Models\ItemManager;

/**
 * Class ItemController
 */
class ItemController extends Controller {
    /** CategoryManager */
    private CategoryManager $cManager;
    /**
     * Init the manager and the validator
     */
    public function __construct() {
        $this->manager = new ItemManager();
        $this->cManager = new CategoryManager();
        parent::__construct();
    }

    /**
     * Render the items edition 
     */
    public function index(): void {
        if(isTeamAdmin()) {
            $categories = $this->cManager->getAll();
            $items = $this->manager->getFromTeam($_SESSION['team']['id']);
            Controller::render('Item/index', ['items' => $items, 'categories' => $categories]);
        } else {
            header('Location: /admins/login');
        }
    }
    
    /**
     * Render the item details 
     */
    public function show(int $id): void {
        if(isset($_SESSION['team'])) {
            $categories = $this->cManager->getAll();
            $item = $this->manager->find($id);
            Controller::render('Item/show', ['item' => $item, 'categories' => $categories]);
        } else {
            header('Location: /admins/login');
        }
    }

    /**
     * Store the new item if valid
     */
    public function store(): void {
        if(isTeamAdmin()) {
            $this->validator->validate([
                'libelle' => ['required', 'min:3', 'max:50'],
                'category' => ['required', 'numeric'],
                'price' => ['required', 'decimal'],
                'total_selled' => ['required', 'numeric']
            ]);

            if(!$this->validator->errors()) {
                $category = $this->cManager->find($_POST['category']);
                if($category) {
                    $id = $this->manager->create($_SESSION['team']['id'], $category->getId(), $_POST['libelle'], $_POST['price'], $_POST['total_selled']);
                    echo json_encode(['success' => true, 'data' => ['id' => $id, 'category' => $category->getLibelle()]]);
                } else {
                    echo json_encode(['success' => false, 'errors' => ['category' => 'La catégorie n\'a pas été trouvée !']]);
                }
            } else {
                echo json_encode(['success' => false, 'errors' => $_SESSION['error']]);
                unset($_SESSION['error']);
            }
        } else {
            echo json_encode(['success' => false, 'errors' => ['message' => 'Vous n\'avez pas l\'abilitation !']]);
        }
    }

    /**
     * Update the item with matching id if valid
     */
    public function update(int $id): void {
        if(isTeamAdmin()) {
            $this->validator->validate([
                'libelle' => ['required', 'min:3', 'max:50'],
                'price' => ['required', 'decimal'],
                'total_selled' => ['required', 'numeric']
            ]);

            if(!$this->validator->errors()) {
                $item = $this->manager->find($id);
                if($item) {
                    $success = $this->manager->update($id, $_POST['libelle'], $_POST['price'], $_POST['total_selled']);
                    if($success !== 0) {
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'errors' => ['message' => 'Une erreur est survenue !']]);
                    }
                } else {
                    echo json_encode(['success' => false, 'errors' => ['message' => 'L\'item n\'a pas été trouvé !']]);
                }
            } else {
                echo json_encode(['success' => false, 'errors' => $_SESSION['error']]);
                unset($_SESSION['error']);
            }
        } else {
            echo json_encode(['success' => false, 'errors' => ['message' => 'Vous n\'avez pas l\'abilitation !']]);
        }
    }

    /**
     * Update the item's category
     */
    public function updateCategory(int $id): void {
        if(isTeamAdmin()) {
            $this->validator->validate([
                'category' => ['required', 'numeric']
            ]);

            if(!$this->validator->errors()) {
                $item = $this->manager->find($id);
                if($item) {
                    $category = $this->cManager->find($_POST['category']);
                    if($category) {
                        $success = $this->manager->updateCategory($id, $category->getId());
                        if($success !== 0) {
                            echo json_encode(['success' => true]);
                        } else {
                            echo json_encode(['success' => false, 'errors' => ['message' => 'Une erreur est survenue !']]);
                        }
                    } else {
                        echo json_encode(['success' => false, 'errors' => ['message' => 'La catégorie n\'a pas été trouvé !']]);
                    }
                } else {
                    echo json_encode(['success' => false, 'errors' => ['message' => 'L\'item n\'a pas été trouvé !']]);
                }
            } else {
                echo json_encode(['success' => false, 'errors' => $_SESSION['error']]);
                unset($_SESSION['error']);
            }
        } else {
            echo json_encode(['success' => false, 'errors' => ['message' => 'Vous n\'avez pas l\'abilitation !']]);
        }
    }

    /**
     * Delete the item with matching id
     */
    public function delete(int $id): void {
        if(isTeamAdmin()) {
            $success = $this->manager->delete($id, $_SESSION['team']['id']);
            if($success !== 0) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'errors' => ['message' => 'Une erreur est survenue !']]);
            }
        } else {
            echo json_encode(['success' => false, 'errors' => ['message' => 'Vous n\'avez pas l\'abilitation !']]);
        }
    }
}