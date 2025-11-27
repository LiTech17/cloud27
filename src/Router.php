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
        
        // 1. Get Request Method and Normalize URI
        $method = $_SERVER['REQUEST_METHOD']; 
        $route = $uri; // The requested URI (e.g., /cloud27-project/admin/projects/2)
        
        $baseDir = rtrim(BASE_PATH, '/');
        // Remove the base directory path from the URI to get the internal route
        if ($baseDir !== '' && strpos($route, $baseDir) === 0) {
            $route = substr($route, strlen($baseDir));
        }
        
        $route = rtrim($route, '/');
        $route = ($route === '') ? '/' : $route; // Ensure root path is '/'

        $foundMatch = false; 
        $params = []; // Will store extracted parameters (e.g., ['id' => '2'])
        $routeInfo = null; 

        // 2. DYNAMIC ROUTE MATCHING AND DISPATCH
        if (isset($this->routes[$method])) {
            
            // Iterate through the defined routes for the current request method
            foreach ($this->routes[$method] as $routePattern => $info) {
                
                // Convert dynamic placeholders ({id}, {slug}) into a regex pattern.
                // It replaces {name} with a named capturing group (?<name>[0-9a-zA-Z]+) 
                // to capture ID or slug values.
                $pattern = preg_replace('#\{([a-zA-Z0-9]+)\}#', '(?<$1>[0-9a-zA-Z]+)', $routePattern);
                
                // Escape forward slashes and anchor the match to start (^) and end ($)
                $pattern = '#^' . str_replace('/', '\/', $pattern) . '$#';

                // Attempt to match the actual request URI against the pattern
                if (preg_match($pattern, $route, $matches)) {
                    
                    $foundMatch = true;
                    $routeInfo = $info; // Store [Controller, Method, Auth]
                    
                    // Extract dynamic parameters from the named captures
                    foreach ($matches as $key => $value) {
                        // Only process named captures (which are strings, not indices 0, 1, 2...)
                        if (is_string($key) && !empty($value)) {
                            $params[$key] = $value;
                        }
                    }
                    
                    // Stop searching on the first match
                    break; 
                }
            }
        }

        // Check if a matching route was found
        if (!$foundMatch) {
            (new \Controllers\ErrorController())->show404(); 
            return;
        }

        // 3. Extract Controller, Method, and Auth Flag
        [$controllerClass, $methodToCall, $authRequired] = $routeInfo;

        // 4. Implement Role-Based Access Control (RBAC) Logic (NO CHANGES HERE)
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

        // 5. Instantiate Controller and Call Method (UPDATED TO PASS PARAMETERS)
        if (class_exists($controllerClass) && method_exists($controllerClass, $methodToCall)) {
            $controller = new $controllerClass();
            // Call the method and pass extracted parameters to it
            // The method signature in the controller must match the route placeholders, e.g., 
            // if route is /admin/projects/{id}, the method must be function show($id)
            $controller->$methodToCall(...$params); 
        } else {
            (new \Controllers\ErrorController())->show404('Internal Error: Controller class or method not found for route.'); 
        }
    }
}