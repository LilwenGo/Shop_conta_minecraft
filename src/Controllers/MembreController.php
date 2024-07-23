<?php
namespace Project\Controllers;
use Project\Models\MembreManager;

/**
 * Class MembreController
 */
class MembreController extends Controller {
    /**
     * Init the manager and the validator
     */
    public function __construct() {
        $this->manager = new MembreManager();
        parent::__construct();
    }

    /**
     * Render the membres edition 
     */
    public function index(): void {
        if(isTeamAdmin()) {
            $membres = $this->manager->getFromTeam($_SESSION['team']['id']);
            Controller::render('Membre/index', ['membres' => $membres]);
        } else {
            header('Location: /membres/login');
        }
    }

    /**
     * Store the new membre if valid
     */
    public function store(): void {
        if(isTeamAdmin()) {
            $this->validator->validate([
                'name' => ['required', 'min:3', 'max:50', 'alphaNum']
            ]);

            if(!$this->validator->errors()) {
                $id = $this->manager->create($_SESSION['team']['id'], $_POST['name']);
                echo json_encode(['success' => true, 'data' => ['id' => $id]]);
            } else {
                echo json_encode(['success' => false, 'errors' => $_SESSION['error']]);
                unset($_SESSION['error']);
            }
        } else {
            echo json_encode(['success' => false, 'errors' => ['message' => 'Vous n\'avez pas l\'abilitation !']]);
        }
    }

    /**
     * Update the membre with matching id if valid
     */
    public function update(int $id): void {
        if(isTeamAdmin()) {
            $this->validator->validate([
                'name' => ['required', 'min:3', 'max:50', 'alphaNum']
            ]);

            if(!$this->validator->errors()) {
                $membre = $this->manager->find($id);
                if($membre) {
                    $success = $this->manager->update($id, $_POST['name']);
                    if($success !== 0) {
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'errors' => ['message' => 'Une erreur est survenue !']]);
                    }
                } else {
                    echo json_encode(['success' => false, 'errors' => ['message' => 'Le membre n\'a pas été trouvé !']]);
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
     * Delete the membre with matching id
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