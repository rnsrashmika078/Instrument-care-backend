<?php

namespace App\Repository;

use App\Core\Database;
use App\DTOs\UserDTO;
use App\DTOs\CreateUserDTO;
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

    public function findByUserName(string $username): ?UserDTO
    {
        $reqColumns = 'id, user_type_id,first_name,last_name,username';
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

    public function createUser(CreateUserDTO $user): void
    {
        $sql = "INSERT INTO users ( user_type_id, first_name,last_name,username) VALUES (?,?,?,?)";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $user->userTypeId,
            $user->firstName,
            $user->lastName,
            $user->username,
        ]);
    }
}
