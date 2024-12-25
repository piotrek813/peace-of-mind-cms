<?php

namespace App;

class Router
{
    protected $routes = [];

    private function addRoute($route, $controller, $action, $method, $middleware = [])
    {
        // Convert route parameters to regex pattern
        $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '(?P<$1>[^/]+)', $route);
        
        $this->routes[$method][$pattern] = [
            'controller' => $controller, 
            'action' => $action,
            'middleware' => $middleware,
            'original' => $route
        ];
    }

    public function get($route, $controller, $action, $middleware = [])
    {
        $this->addRoute($route, $controller, $action, "GET", $middleware);
    }

    public function post($route, $controller, $action, $middleware = [])
    {
        $this->addRoute($route, $controller, $action, "POST", $middleware);
    }

    public function delete($route, $controller, $action, $middleware = [])
    {
        $this->addRoute($route, $controller, $action, "DELETE", $middleware);
    }

    public function dispatch()
    {
        $uri = strtok($_SERVER['REQUEST_URI'], '?');
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$method] ?? [] as $pattern => $route) {
            // Add start and end delimiters to pattern
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $uri, $matches)) {
                // Remove numeric keys from matches
                $params = array_filter($matches, function($key) {
                    return !is_numeric($key);
                }, ARRAY_FILTER_USE_KEY);
                
                foreach ($route['middleware'] as $middlewareClass) {
                    $middleware = new $middlewareClass();
                    $middleware->handle();
                }

                $controller = $route['controller'];
                $action = $route['action'];

                $controller = new $controller();
                return $controller->$action(...array_values($params));
            }
        }

        throw new \Exception("No route found for URI: $uri");
    }
}
