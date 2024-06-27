<?php
namespace Project;
use Project\Controllers\Controller;

/** Class Router **/

class Router {

    /**
     * Curent url
     */
    private string $url;
    /**
     * Existing routes
     */
    private array $routes = [];

    /**
     * Set the curent url
     */
    public function __construct(string $url) {
        $this->url = $url;
    }

    /**
     * Store a new route with method GET
     */
    public function get(string $path, string $callable): Route {
        $route = new Route($path, $callable);
        $this->routes["GET"][] = $route;
        return $route;
    }

    /**
     * Store a new route with method POST
     */
    public function post(string $path, string $callable): Route {
        $route = new Route($path, $callable);
        $this->routes["POST"][] = $route;
        return $route;
    }

    /**
     * Run the matching route
     */
    public function run(): void {
        if(isset($this->routes[$_SERVER['REQUEST_METHOD']])){
            foreach($this->routes[$_SERVER['REQUEST_METHOD']] as $route){
                if($route->match($this->url)){
                    return $route->call();
                }
            }
        }
        Controller::render('404');
    }

}
