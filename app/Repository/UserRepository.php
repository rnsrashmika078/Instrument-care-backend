<?php

namespace App\Repository;

use App\Core\Database;
use App\Core\Response;
use App\DTOs\UserDTO;
use App\DTOs\CreateUserDTO;
use PDO;
use PDOException;

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

    public function findByUserName(string $username): ?UserDTO
    {
        $reqColumns = 'id, user_type_id,first_name,last_name,username, password';
        // $reqColumns = 'id, user_type_id, institute_id, faculty_id, department_id,laboratory_id,title,first_name,last_name,designation,address,mobile_number,phone_number,gender,email,picture,oauth_provider,oauth_uid,locale,link,created,modified,last_login_date,last_login_location,username,user_status,is_email_verified';
        $sql = "SELECT $reqColumns FROM users WHERE username = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return null;
        }
        return UserDTO::fromArray($user);
    }
    // for login check
    public function findForLogin(string $username): ?array
    {
        $sql = "
        SELECT id, user_type_id, first_name,last_name,username, password
        FROM users
        WHERE username = ?
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }
    public function createUser(CreateUserDTO $user): void
    {
        try {
            $sql = "INSERT INTO users ( user_type_id, first_name,last_name,username, password) VALUES (?,?,?,?,?)";
            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                $user->userTypeId,
                $user->firstName,
                $user->lastName,
                $user->username,
                $user->password,
            ]);
        } catch (PDOException $e) {
            Response::json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
