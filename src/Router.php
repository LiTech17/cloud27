<?php
// src/Router.php

class Router {
    private array $routes;

    public function __construct(array $routes) {
        // $routes should now be a nested array, e.g., ['GET' => [...], 'POST' => [...]]
        $this->routes = $routes;
    }

    /**
     * Finds the correct Controller and method for the given URI and executes it.
     */
    public function dispatch(string $uri): void {
        
        // 1. Get Request Method and Normalize URI
        $method = $_SERVER['REQUEST_METHOD']; // Determine the request method (e.g., 'GET', 'POST')
        
        $baseDir = dirname($_SERVER['SCRIPT_NAME']);
        if ($baseDir !== '/' && strpos($uri, $baseDir) === 0) {
            $uri = substr($uri, strlen($baseDir));
        }
        
        $uri = rtrim($uri, '/');
        $uri = ($uri === '') ? '/' : $uri;

        // 2. Check if the route exists for the given METHOD and URI
        if (!isset($this->routes[$method]) || !isset($this->routes[$method][$uri])) {
            // If the route or method is not defined, show 404
            (new \Controllers\ErrorController())->show404(); 
            return;
        }

        // 3. Extract Controller and Method
        [$controllerClass, $methodToCall] = $this->routes[$method][$uri];

        // 4. Instantiate Controller and Call Method
        if (class_exists($controllerClass) && method_exists($controllerClass, $methodToCall)) {
            $controller = new $controllerClass();
            $controller->$methodToCall(); // Execute the correct action (e.g., showForm or submitForm)
        } else {
            // Use the FQN for the ErrorController
            (new \Controllers\ErrorController())->show404('Internal Error: Controller class or method not found for route.'); 
        }
    }
}