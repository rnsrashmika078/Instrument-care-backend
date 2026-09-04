<?php

namespace App\Core;

class Response
{
    public static function json(array $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-type: application/json');
        echo json_encode($data);
        exit;
    }
}
// static ::
// instance ->