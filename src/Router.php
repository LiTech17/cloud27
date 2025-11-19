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
        $method = $_SERVER['REQUEST_METHOD']; 
        $route = $uri; // Use $route for checks
        
        $baseDir = dirname($_SERVER['SCRIPT_NAME']);
        if ($baseDir !== '/' && strpos($route, $baseDir) === 0) {
            $route = substr($route, strlen($baseDir));
        }
        
        $route = rtrim($route, '/');
        $route = ($route === '') ? '/' : $route;

        // 2. Check if the route exists for the given METHOD and URI
        if (!isset($this->routes[$method]) || !isset($this->routes[$method][$route])) {
            (new \Controllers\ErrorController())->show404(); 
            return;
        }

        // 3. Extract Controller, Method, and Auth Flag
        [$controllerClass, $methodToCall, $authRequired] = $this->routes[$method][$route]; // <-- EXTRACT $authRequired

        // 4. Implement Role-Based Access Control (RBAC) Logic
        if ($authRequired) {
            
            // Check if logged in at all
            if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
                // Not logged in -> Redirect to login page
                header('Location: ' . BASE_PATH . '/login');
                exit;
            }
            
            $isAdmin = $_SESSION['is_admin'] ?? false;
            
            // Enforce Role Check based on the URL prefix
            if (str_starts_with($route, '/admin')) {
                // Only Admins (is_admin = 1) can access /admin routes
                if (!$isAdmin) {
                    // Redirect non-admins (Clients) to their own dashboard
                    header('Location: ' . BASE_PATH . '/client/dashboard'); 
                    exit;
                }
            } elseif (str_starts_with($route, '/client')) {
                // Only Clients (is_admin = 0) should be here
                if ($isAdmin) {
                    // Redirect Admins away from the client dashboard
                    header('Location: ' . BASE_PATH . '/admin/dashboard'); 
                    exit;
                }
            }
        }

        // 5. Instantiate Controller and Call Method
        if (class_exists($controllerClass) && method_exists($controllerClass, $methodToCall)) {
            $controller = new $controllerClass();
            $controller->$methodToCall(); 
        } else {
            (new \Controllers\ErrorController())->show404('Internal Error: Controller class or method not found for route.'); 
        }
    }
}