<?php
// src/Router.php

class Router {
    private array $routes;

    public function __construct(array $routes) {
        $this->routes = $routes;
    }

    /**
     * Finds the correct Controller and method for the given URI and executes it.
     */
    public function dispatch(string $uri): void {
        // 1. Normalize URI... (code remains the same)
        $baseDir = dirname($_SERVER['SCRIPT_NAME']);
        if ($baseDir !== '/' && strpos($uri, $baseDir) === 0) {
            $uri = substr($uri, strlen($baseDir));
        }
        
        $uri = rtrim($uri, '/');
        $uri = ($uri === '') ? '/' : $uri;


        // 2. Check if the route exists
        if (!isset($this->routes[$uri])) {
            // FIXED: Use the FQN for the ErrorController
            (new \Controllers\ErrorController())->show404(); 
            return;
        }

        // 3. Extract Controller and Method
        [$controllerClass, $method] = $this->routes[$uri]; // $controllerClass is already FQN (e.g., Controllers\HomeController)

        // 4. Instantiate Controller and Call Method
        if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
            $controller = new $controllerClass();
            $controller->$method();
        } else {
            // FIXED: Use the FQN for the ErrorController
            (new \Controllers\ErrorController())->show404('Internal Error: Controller class or method not found for route.'); 
        }
    }
}