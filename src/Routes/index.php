<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Router;
use App\Middleware\SessionMiddleware;
use App\Middleware\AuthMiddleware;

$router = new Router();

$router->addStaticDirectory('/assets', __DIR__ . '/../public/assets');

// Public routes
$router->get('/', HomeController::class, 'index');
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

$router->dispatch();
