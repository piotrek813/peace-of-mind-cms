<?php

namespace App;

class Router
{
    protected $routes = [];
    private $staticDirs = [];

    public function addStaticDirectory(string $urlPath, string $filesystemPath)
    {
        $this->staticDirs[$urlPath] = rtrim($filesystemPath, '/');
    }

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

    private function serveStaticFile(string $uri): bool
    {
        foreach ($this->staticDirs as $urlPath => $filesystemPath) {
            if (strpos($uri, $urlPath) === 0) {
                $relativePath = substr($uri, strlen($urlPath));
                $filePath = $filesystemPath . '/' . $relativePath;

                if (!file_exists($filePath)) {
                    continue;
                }

                $mimeTypes = [
                    'css' => 'text/css',
                    'js' => 'application/javascript',
                    'png' => 'image/png',
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'gif' => 'image/gif',
                    'svg' => 'image/svg+xml',
                ];

                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                $contentType = $mimeTypes[$extension] ?? 'application/octet-stream';

                header("Content-Type: $contentType");
                readfile($filePath);
                return true;
            }
        }
        return false;
    }

    public function dispatch()
    {
        $uri = strtok($_SERVER['REQUEST_URI'], '?');
        $method = $_SERVER['REQUEST_METHOD'];

        // Try to serve static file first
        if ($this->serveStaticFile($uri)) {
            return;
        }

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
