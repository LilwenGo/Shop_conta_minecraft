<?php
namespace Project\Controllers;
use Project\Models\AdminManager;
use Project\Models\TeamManager;

/**Class TeamController */
class TeamController extends Controller {
    /**
     * Init the manager and call the parent's constructor
     */
    public function __construct() {
        $this->manager = new TeamManager();
        parent::__construct();
    }

    /**
     * Render the homepage
     */
    public function index(): void {
        if(isset($_SESSION['team'])) {
            header('Location: /team');
        } else {
            Controller::render('index');
        }
    }

    /**
     * Render the team view
     */
    public function show(): void {
        if(isset($_SESSION['team'])) {
            $team = $this->manager->find($_SESSION['team']['id']);
            Controller::render('/Team/show', ['team' => $team]);
        } else {
            header('Location: /team/login');
        }
    }

    /**
     * Render the login view
     */
    public function showLogin(): void {
        if(!isset($_SESSION['team'])) {
            Controller::render('Team/login');
        } else {
            header('Location: /team');
        }
    }

    /**
     * Render the register view
     */
    public function showRegister(): void {
        if(!isset($_SESSION['team'])) {
            Controller::render('Team/register');
        } else {
            header('Location: /team');
        }
    }

    /**
     * Log the team in if inputs are valid
     */
    public function login(): void {
        if(!isset($_SESSION['team'])) {
            $this->validator->validate([
                'name' => ['required', 'min:3'],
                'password' => ['required', 'min:6', 'max:10', 'alphaNum']
            ]);
            $_SESSION['old'] = $_POST;

            if(!$this->validator->errors()) {
                $team = $this->manager->getByLogin(escape($_POST['name']));
                if($team && password_verify($_POST['password'], $team->getPassword())) {
                    $_SESSION['team'] = [
                        'id' => $team->getId(),
                        'name' => $team->getLogin()
                    ];
                    header('Location: /team');
                } else {
                    $_SESSION['error']['name'] = 'Erreur sur les identifiants !';
                    header('Location: /team/login');
                }
            } else {
                header('Location: /team/login');
            }
        } else {
            header('Location: /team');
        }
    }

    /**
     * Sign up the team if inputs are valid
     */
    public function register(): void {
        if(!isset($_SESSION['team'])) {
            $this->validator->validate([
                'name' => ['required', 'min:3'],
                'password' => ['required', 'min:6', 'max:10', 'alphaNum']
            ]);
            $_SESSION['old'] = $_POST;

            if(!$this->validator->errors()) {
                $team = $this->manager->getByLogin(escape($_POST['name']));
                if(!$team) {
                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $id = $this->manager->create(escape($_POST['name']), $password);
                    $am = new AdminManager();
                    $login = time();
                    $am->create($id, $login, $password);
                    $_SESSION['team'] = [
                        'id' => $id,
                        'name' => escape($_POST['name'])
                    ];
                    $_SESSION['old']['message'] = 'L\'équipe a été créée ! Un compte administrateur a aussi été créé avec le même mot de passe que votre équipe !';
                    header('Location: /team');
                } else {
                    $_SESSION['error']['name'] = 'Le nom d\'équipe est déja pris !';
                    header('Location: /team/register');
                }
            } else {
                header('Location: /team/register');
            }
        } else {
            header('Location: /team');
        }
    }

    /**
     * Log out the team
     */
    public function logout(): void {
        session_start();
        session_destroy();
        header('Location: /');
    }
}