<?php
namespace Project\Controllers;
use Project\Models\TeamManager;

class TeamController extends Controller {
    public function __construct() {
        $this->manager = new TeamManager();
        parent::__construct();
    }

    public function index(): void {
        Controller::render('index');
    }
}