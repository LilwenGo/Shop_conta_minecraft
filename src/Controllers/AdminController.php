<?php
namespace Project\Controllers;
use Project\Models\AdminManager;

/**Class AdminController */
class AdminController extends Controller {
    /**
     * Init the manager and the validator
     */
    public function __construct() {
        $this->manager = new AdminManager();
        parent::__construct();
    }

    /**
     * Render the admins edition 
     */
    public function index(): void {
        if(isTeamAdmin()) {
            $admins = $this->manager->getFromTeam($_SESSION['team']['id']);
            Controller::render('Admin/index', ['admins' => $admins]);
        } else {
            header('Location: /admins/login');
        }
    }

    /**
     * Render the login view
     */
    public function showLogin(): void {
        if(!isTeamAdmin()) {
            Controller::render('Admin/login');
        } else {
            header('Location: /team');
        }
    }

    /**
     * Log the team in if inputs are valid
     */
    public function login(): void {
        if(!isTeamAdmin()) {
            $this->validator->validate([
                'name' => ['required', 'min:3'],
                'password' => ['required', 'min:6', 'max:10', 'alphaNum']
            ]);
            $_SESSION['old'] = $_POST;

            if(!$this->validator->errors()) {
                $admin = $this->manager->getByLogin(escape($_POST['name']));
                if($admin && password_verify($_POST['password'], $admin->getPassword())) {
                    $_SESSION['admin'] = [
                        'id' => $admin->getId(),
                        'name' => $admin->getLogin()
                    ];
                    $_SESSION['old']['message'] = 'Vous êtes maintenant connecté en tant qu\'administrateur !';
                    header('Location: /team');
                } else {
                    $_SESSION['error']['name'] = 'Erreur sur les identifiants !';
                    header('Location: /admins/login');
                }
            } else {
                header('Location: /admins/login');
            }
        } else {
            header('Location: /team');
        }
    }

    /**
     * Store the new admin if valid
     */
    public function store(): void {
        if(isTeamAdmin()) {
            $this->validator->validate([
                'name' => ['required', 'min:3', 'max:50', 'alphaNum'],
                'password' => ['required', 'min:6', 'max:10', 'alphaNum']
            ]);

            if(!$this->validator->errors()) {
                $admin = $this->manager->getByLogin($_POST['name']);
                if(!$admin) {
                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $id = $this->manager->create($_SESSION['team']['id'], $_POST['name'], $password);
                    echo json_encode(['success' => true, 'data' => ['id' => $id]]);
                } else {
                    echo json_encode(['success' => false, 'errors' => ['name' => 'Ce nom d\'administrateur est déja pris !']]);
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
     * Update the admin with matching id if valid
     */
    public function update(int $id): void {
        if(isTeamAdmin()) {
            $this->validator->validate([
                'name' => ['required', 'min:3', 'max:50', 'alphaNum']
            ]);

            if(!$this->validator->errors()) {
                $admin = $this->manager->getByLogin($_POST['name']);
                if(!$admin) {
                    $admin = $this->manager->find($id);
                    if($admin) {
                        $success = $this->manager->update($id, $_POST['name'], $admin->getPassword());
                        if($success !== 0) {
                            echo json_encode(['success' => true]);
                        } else {
                            echo json_encode(['success' => false, 'errors' => ['message' => 'Une erreur est survenue !']]);
                        }
                    } else {
                        echo json_encode(['success' => false, 'errors' => ['message' => 'L\'administrateur n\'a pas été trouvé !']]);
                    }
                } else {
                    echo json_encode(['success' => false, 'errors' => ['name' => 'Ce nom d\'administrateur est déja pris !']]);
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
     * Delete the admin with matching id
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
     * Log out the admin
     */
    public function logout(): void {
        unset($_SESSION['admin']);
        header('Location: /team');
    }
}