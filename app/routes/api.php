<?php

use App\Controller\AuthController;
use App\Core\Router;
use App\Controller\UserController;

$router = new Router();

$router->get('/api/test', [UserController::class, 'test']);

$router->get('/api/users', [UserController::class, 'index']);

$router->get('/api/users', action: [UserController::class, 'find']);

// $router->post('/api/users', [UserController::class, 'store']);

//authentication routes
$router->post('/api/auth/register', [AuthController::class, 'register']);
$router->post('/api/auth/login', [AuthController::class, 'login']);
$router->get('/api/auth/me', [AuthController::class, 'authUser']);

$router->dispatch();
