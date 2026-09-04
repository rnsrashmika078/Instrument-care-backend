<?php

namespace App\Controller;

use App\Core\Response;
use App\Repository\UserRepository;
use PDOException;

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
        try {
            $payload = $_POST;
            Response::json([
                'message' => 'User creation is not implemented yet.',
                'payload' => $payload,
            ], 501);
        } catch (PDOException $e) {
            Response::json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function find(): void
    {
        try {
            $username = $_GET['username'] ?? null;
            if (!$username) {
                Response::json([
                    'message' => 'Username is required',
                ], 400);
            }
            $user = $this->userRepository->findByUserName($username);
            if ($user === null) {
                Response::json([
                    'message' => 'user not found',
                ], 404);
            }

            Response::json([
                'data' => $user,
            ]);
        } catch (PDOException $e) {
            Response::json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
