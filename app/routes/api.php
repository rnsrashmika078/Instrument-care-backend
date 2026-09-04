<?php

use App\Core\Router;
use App\Controller\UserController;

$router = new Router();

$router->get('/api/test', [UserController::class, 'test']);

$router->get('/api/users', [UserController::class, 'index']);

$router->get('/api/users', action: [UserController::class, 'find']);

$router->post('/api/users', [UserController::class, 'store']);

$router->dispatch();
