<?php
namespace Project\Controllers;
use Project\Validator;

abstract class Controller {
    protected Object $manager;
    protected Validator $validator;

    public function __construct() {
        $this->validator = new Validator();
    }

    static public function render(string $view, array $data = []) {
        extract($data);

        ob_start();

        require VIEWS."$view.php";

        $content = ob_get_clean();

        require_once VIEWS.'layout.php';
    }
}