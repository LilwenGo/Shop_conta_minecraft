<?php
namespace Project\Controllers;
use Project\Models\CategoryManager;
use Project\Models\TeamManager;
use Project\Models\MembreManager;

/** Class CategoryController */
class CategoryController extends Controller {
    /**
     * TeamManager
     */
    private TeamManager $tManager;
    
    /**
     * MembreManager
     */
    private MembreManager $mManager;
    /**
     * Init the manager and the validator
     */
    public function __construct() {
        $this->manager = new CategoryManager();
        $this->tManager = new TeamManager();
        $this->mManager = new MembreManager();
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
     * Render the category details 
     */
    public function show(int $id): void {
        if(isset($_SESSION['team'])) {
            $team = $this->tManager->find($_SESSION['team']['id']);
            if($team) {
                $category = $this->manager->find($id);
                if($category) {
                    $inTeam = false;
                    foreach($team->getCategories() as $tcategory) {
                        if($category->getId() === $tcategory->getId()) {
                            $inTeam = true;
                            break;
                        }
                    }
                    if($inTeam) {
                        Controller::render('Category/show', ['category' => $category]);
                    } else {
                        Controller::render('error', ['code' => 404, 'message' => 'Impossible de trouver la catégorie !']);
                    }
                } else {
                    Controller::render('error', ['code' => 404, 'message' => 'Impossible de trouver la catégorie !']);
                }
            } else {
                Controller::render('error', ['code' => 404, 'message' => 'Impossible de trouver la catégorie !']);
            }
        } else {
            header('Location: /team/login');
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
                echo json_encode(['success' => true, 'data' => ['id' => $id, 'libelle' => $_POST['libelle']]]);
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

    /**
     * Add a membre to the category
     */
    public function addMembre(int $id, int $id_membre): void {
        if(isTeamAdmin()) {
            $this->validator->validate([
                'role' => ['required', 'min:3', 'max:20', 'alphaSpaceAccent']
            ]);

            if(!$this->validator->errors()) {
                $team = $this->tManager->find($_SESSION['team']['id']);
                if($team) {
                    $category = $this->manager->find($id);
                    $membre = $this->mManager->find($id_membre);
                    if($category && $membre) {
                        $cInTeam = false;
                        $mInTeam = false;
                        foreach($team->getCategories() as $tcategory) {
                            if($category->getId() === $tcategory->getId()) {
                                $cInTeam = true;
                                break;
                            }
                        }
                        foreach($team->getMembres() as $tmembre) {
                            if($membre->getId() === $tmembre->getId()) {
                                $mInTeam = true;
                                break;
                            }
                        }
                        if($cInTeam && $mInTeam) {
                            $success = $this->manager->addMembre($category->getId(), $membre->getId(), $_POST['role']);
                            if($success) {
                                echo json_encode(['success' => true, 'data' => ['libelle' => $category->getLibelle()]]);
                            } else {
                                echo json_encode(['success' => false, 'errors' => ['message' => 'Impossible d\'associer la catégorie !']]);
                            }
                        } else {
                            echo json_encode(['success' => false, 'errors' => ['message' => 'Impossible d\'associer la catégorie !']]);
                        }
                    } else {
                        echo json_encode(['success' => false, 'errors' => ['message' => 'Impossible d\'associer la catégorie !']]);
                    }
                } else {
                    echo json_encode(['success' => false, 'errors' => ['message' => 'Impossible d\'associer la catégorie !']]);
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
     * Update a membre to the category
     */
    public function setMembre(int $id, int $id_membre): void {
        if(isTeamAdmin()) {
            $this->validator->validate([
                'role' => ['required', 'min:3', 'max:20', 'alphaSpaceAccent']
            ]);

            if(!$this->validator->errors()) {
                $team = $this->tManager->find($_SESSION['team']['id']);
                if($team) {
                    $category = $this->manager->find($id);
                    $membre = $this->mManager->find($id_membre);
                    if($category && $membre) {
                        $cInTeam = false;
                        $mInTeam = false;
                        foreach($team->getCategories() as $tcategory) {
                            if($category->getId() === $tcategory->getId()) {
                                $cInTeam = true;
                                break;
                            }
                        }
                        foreach($team->getMembres() as $tmembre) {
                            if($membre->getId() === $tmembre->getId()) {
                                $mInTeam = true;
                                break;
                            }
                        }
                        if($cInTeam && $mInTeam) {
                            $success = $this->manager->setMembre($category->getId(), $membre->getId(), $_POST['role']);
                            if($success) {
                                echo json_encode(['success' => true]);
                            } else {
                                echo json_encode(['success' => false, 'errors' => ['message' => 'Impossible d\'associer la catégorie !']]);
                            }
                        } else {
                            echo json_encode(['success' => false, 'errors' => ['message' => 'Impossible d\'associer la catégorie !']]);
                        }
                    } else {
                        echo json_encode(['success' => false, 'errors' => ['message' => 'Impossible d\'associer la catégorie !']]);
                    }
                } else {
                    echo json_encode(['success' => false, 'errors' => ['message' => 'Impossible d\'associer la catégorie !']]);
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
     * Delete a membre to the category
     */
    public function deleteMembre(int $id, int $id_membre): void {
        if(isTeamAdmin()) {
            $success = $this->manager->deleteMembre($id, $id_membre);
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