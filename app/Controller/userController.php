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
