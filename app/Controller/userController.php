<?php

namespace App\Controller;

use App\Core\Response;
use App\Exceptions\NotFoundException;
use App\DTOs\CreateUserDTO;
use App\Repository\UserRepository;
use PDOException;
use App\Validation\UserValidator;


class UserController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function test(): string
    {
        return "hello";
    }

    public function index(): void
    {
        try {
            $users = $this->userRepository->all();
            Response::json([
                'data' => $users,
                'count' => count($users),
            ]);
        } catch (PDOException $e) {
            Response::json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(): void
    {
        $payload = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (!is_array($payload)) {
            Response::json([
                'message' => 'Invalid request body'
            ], 400);
        }
        $errors = UserValidator::validate($payload);


        if (!empty($errors)) {
            Response::json([
                'message' => 'Validation failed',
                'errors' => $errors
            ], 422);
        }
        $user = new CreateUserDTO(
            userTypeId: (int) $payload['user_type_id'],
            firstName: $payload['first_name'] ?? null,
            lastName: $payload['last_name'] ?? null,
            username: $payload['username']
        );

        $this->userRepository->createUser($user);

        Response::json([
            'message' => 'New User Created!',
        ], 201);
    }

    public function find(): void
    {
        $username = $_GET['username'] ?? null;
        if (!$username) {
            Response::json([
                'message' => 'Username is required',
            ], 400);
        }
        $user = $this->userRepository->findByUserName($username);
        if ($user === null) {
            throw new NotFoundException('User not found!');
        }

        Response::json([
            'data' => $user,
        ]);
    }
}
