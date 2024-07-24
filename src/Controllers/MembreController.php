<?php
namespace Project\Controllers;
use Project\Models\MembreManager;
use Project\Models\TeamManager;

/**
 * Class MembreController
 */
class MembreController extends Controller {
    /**
     * TeamManager
     */
    private TeamManager $tmanager;
    /**
     * Init the manager and the validator
     */
    public function __construct() {
        $this->manager = new MembreManager();
        $this->tmanager = new TeamManager();
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
            header('Location: /admins/login');
        }
    }

    /**
     * Render the show view
     */
    public function show(int $id): void {
        if(isset($_SESSION['team']['id'])) {
            $team = $this->tmanager->find($_SESSION['team']['id']);
            if($team) {
                $membre = $this->manager->find($id);
                if($membre) {
                    $inTeam = false;
                    foreach($team->getMembres() as $tmembre) {
                        if($membre->getId() === $tmembre->getId()) {
                            $inTeam = true;
                            break;
                        }
                    }
                    if($inTeam) {
                        Controller::render('Membre/show', ['membre' => $membre, 'categories' => $team->getCategories()]);
                    }
                } else {
                    Controller::render('error', ['code' => 404, 'message' => 'Impossible de trouver le membre !']);
                }
            } else {
                Controller::render('error', ['code' => 404, 'message' => 'Impossible de trouver le membre !']);
            }
        } else {
            header('Location: /team/login');
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