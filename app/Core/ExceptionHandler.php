<?php


namespace App\Core;

use App\Core\Response;
use App\Exceptions\NotFoundException;
use App\Exceptions\PDOException;
use Throwable;

class ExceptionHandler
{
    public static function handle(Throwable $e): never
    {
        if ($e instanceof NotFoundException) {
            Response::json([
                'message' => $e->getMessage()
            ], 404);
        }

        // if ($e instanceof ValidationException) {
        //     Response::json([
        //         'message' => $e->getMessage()
        //     ], 422);
        // }

        if ($e instanceof PDOException) {
            Response::json([
                'message' => $e->getMessage()
            ], 401);
        }

        error_log($e);

        Response::json([
            'message' => 'Internal server error'
        ], 500);

        exit;
    }
}
