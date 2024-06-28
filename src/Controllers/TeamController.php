<?php
namespace Project\Controllers;
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
        Controller::render('index');
    }
}