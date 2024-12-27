<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\MediaController;
use App\Controllers\ContentController;
use App\Router;
use App\Middleware\SessionMiddleware;
use App\Middleware\AuthMiddleware;

$router = new Router();

// Public routes
$router->get('/', HomeController::class, 'index', [SessionMiddleware::class]);
$router->get('/login', AuthController::class, 'loginIndex');
$router->post('/login', AuthController::class, 'login', [SessionMiddleware::class]);
$router->get('/register', AuthController::class, 'registerIndex');
$router->post('/register', AuthController::class, 'register');

// Protected routes
$router->get('/dashboard', DashboardController::class, 'index', [SessionMiddleware::class, AuthMiddleware::class]);
$router->get('/editor', DashboardController::class, 'editor', [SessionMiddleware::class, AuthMiddleware::class]);
$router->post('/editor', DashboardController::class, 'saveEntry', [SessionMiddleware::class, AuthMiddleware::class]);
$router->get('/logout', AuthController::class, 'logout', [SessionMiddleware::class, AuthMiddleware::class]);
$router->post('/delete-entry', DashboardController::class, 'deleteEntry', [SessionMiddleware::class, AuthMiddleware::class]);

$router->get('/media-library', MediaController::class, 'index', [SessionMiddleware::class, AuthMiddleware::class]);
$router->post('/media-library/upload', MediaController::class, 'upload', [SessionMiddleware::class, AuthMiddleware::class]);
$router->delete('/media-library/{id}', MediaController::class, 'delete', [SessionMiddleware::class, AuthMiddleware::class]);

$router->get('/content', ContentController::class, 'query');

$router->dispatch();
