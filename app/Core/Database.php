<?php

namespace App\Core;

use Exception;
use PDO;
use PDOException;

class Database
{

    public function connect(): PDO
    {
        try {
            return new PDO(
                'mysql:host=localhost;dbname=instrument;charset=utf8mb4',
                'root',
                ''
            );
        } catch (PDOException $e) {
            throw new Exception(
                'Database connection failed: ' . $e->getMessage()
            );
        }
    }
}
