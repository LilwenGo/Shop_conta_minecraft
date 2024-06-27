<?php
namespace Project;

/** Class Route */
class Route {

    /**
     * Route's url
     */
    private string $path;
    /**
     * Method to call
     */
    private string $callable;
    /**
     * Array containing the queries
     */
    private array $matches = [];

    /**
     * Set the path and the callable
     */
    public function __construct(string $path, string $callable) {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    /**
     * Verify the route match the passed url
     */
    public function match(string $url): bool{
        $url = trim($url, '/');
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);
        $regex = "#^$path$#i";
        if(!preg_match($regex, $url, $matches)){
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    /**
     * Call the method of the callable
     */
    public function call(): void {
         $rep = explode("@", $this->callable);
         $controller = "Project\\Controllers\\".$rep[0];
         $controller = new $controller();

        return call_user_func_array([$controller, $rep[1]], $this->matches);
    }

}
