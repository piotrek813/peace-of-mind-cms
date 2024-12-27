<?php

namespace App;

class Router
{
    protected $routes = [];

    private function addRoute($route, $controller, $action, $method, $middleware = [])
    {
        $this->routes[$method][$route] = [
            'controller' => $controller, 
            'action' => $action,
            'middleware' => $middleware
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

    public function dispatch()
    {
        $uri = strtok($_SERVER['REQUEST_URI'], '?');
        $uri = str_replace(URL_PREFIX, '', $uri);
        $method = $_SERVER['REQUEST_METHOD'];


        if (array_key_exists($uri, $this->routes[$method])) {
            $route = $this->routes[$method][$uri];
            
            foreach ($route['middleware'] as $middlewareClass) {
                $middleware = new $middlewareClass();
                $middleware->handle();
            }

            $controller = $route['controller'];
            $action = $route['action'];

            $controller = new $controller();
            $controller->$action();
        } else {
            throw new \Exception("No route found for URI: $uri");
        }
    }
}
