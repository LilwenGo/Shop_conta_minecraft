<?php
namespace Project\Controllers;
use Project\Validator;

/**Class Controller */
abstract class Controller {
    /**
     * The controller's manager
     */
    protected Object $manager;
    /**
     * Validator
     */
    protected Validator $validator;

    /**
     * Instantiate the validator
     */
    public function __construct() {
        $this->validator = new Validator();
    }

    /**
     * Render the passed view
     * $data is optional, if you want to send a variable to the view you have to send it in the data: ['variable' => value] 
     */
    static public function render(string $view, array $data = []): void {
        extract($data);

        ob_start();

        require VIEWS."$view.php";

        $content = ob_get_clean();

        require_once VIEWS.'layout.php';
    }
}