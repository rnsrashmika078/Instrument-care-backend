<?php


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(
    dirname(__DIR__)
);
$dotenv->load();
set_exception_handler([App\Core\ExceptionHandler::class, 'handle']);

require_once __DIR__ . '/../app/routes/api.php';
