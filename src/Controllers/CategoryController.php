<?php
namespace Project\Controllers;
use Project\Models\CategoryManager;

/** Class CategoryController */
class CategoryController extends Controller {
    /**
     * Init the manager and the validator
     */
    public function __construct() {
        $this->manager = new CategoryManager();
        parent::__construct();
    }

    /**
     * Render the categories edition 
     */
    public function index(): void {
        if(isTeamAdmin()) {
            $categories = $this->manager->getFromTeam($_SESSION['team']['id']);
            Controller::render('Category/index', ['categories' => $categories]);
        } else {
            header('Location: /admins/login');
        }
    }

    /**
     * Store the new category if valid
     */
    public function store(): void {
        if(isTeamAdmin()) {
            $this->validator->validate([
                'libelle' => ['required', 'min:3', 'max:50']
            ]);

            if(!$this->validator->errors()) {
                $id = $this->manager->create($_SESSION['team']['id'], $_POST['libelle']);
                echo json_encode(['success' => true, 'data' => ['id' => $id, 'libelle' => escape($_POST['libelle'])]]);
            } else {
                echo json_encode(['success' => false, 'errors' => $_SESSION['error']]);
                unset($_SESSION['error']);
            }
        } else {
            echo json_encode(['success' => false, 'errors' => ['message' => 'Vous n\'avez pas l\'abilitation !']]);
        }
    }

    /**
     * Update the category with matching id if valid
     */
    public function update(int $id): void {
        if(isTeamAdmin()) {
            $this->validator->validate([
                'libelle' => ['required', 'min:3', 'max:50']
            ]);

            if(!$this->validator->errors()) {
                $category = $this->manager->find($id);
                if($category) {
                    $success = $this->manager->update($id, $_POST['libelle']);
                    if($success !== 0) {
                        echo json_encode(['success' => true, 'data' => ['libelle' => escape($_POST['libelle'])]]);
                    } else {
                        echo json_encode(['success' => false, 'errors' => ['message' => 'Une erreur est survenue !']]);
                    }
                } else {
                    echo json_encode(['success' => false, 'errors' => ['message' => 'La catégorie n\'a pas été trouvée !']]);
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
     * Delete the category with matching id
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