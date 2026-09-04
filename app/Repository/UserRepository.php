<?php

namespace App\Repository;

use App\Core\Database;
use PDO;

class UserRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->connect();
    }

    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT id, user_type_id, username, password FROM users ORDER BY id ASC');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByUserName(string $username): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, user_type_id, username, password FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }
}
