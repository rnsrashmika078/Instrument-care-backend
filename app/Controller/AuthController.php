<?php

namespace App\Controller;

use App\Core\Response;
use App\DTOs\UserDTO;
use App\Exceptions\NotFoundException;
use App\DTOs\CreateUserDTO;
use App\Repository\UserRepository;
use App\Security\Jwt;
use App\Validation\UserLoginValidator;
use PDOException;
use App\Validation\UserRegisterValidator;

class AuthController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }


    public function login()
    {
        $payload = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (!is_array($payload)) {
            Response::json([
                'message' => 'Invalid request body',
                'success' => false,
            ], 400);
        }
        $errors = UserLoginValidator::validate($payload);

        if (!empty($errors)) {
            Response::json([
                'message' => 'Validation failed',
                'errors' => $errors,
                'success' => false,
            ], 422);
        }
        $username = $payload['username'];
        $password = $payload['password'];
        $user = $this->userRepository->findForLogin($username);


        // var_dump($user);

        if (empty($user)) {
            Response::json([
                'message' => 'User Not Found!',
                'success' => false,
            ], 404);
        }

        if (!$user || !password_verify($password, $user['password'])) {
            Response::json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = Jwt::generate($payload['username']);

        setcookie('token', $token, [
            'expires' => time() + 3600,
            'httponly' => true,
            'secure' => false,
            'samesite' => 'Lax',
            'path' => '/',
        ]);

        Response::json([
            'message' => 'User logged in successfully!',
            'success' => true,
            'user' => UserDTO::fromArray($user)
        ], 200);
    }

    public function register(): void
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
        $errors = UserRegisterValidator::validate($payload);

        if (!empty($errors)) {
            Response::json([
                'message' => 'Validation failed',
                'errors' => $errors
            ], 422);
        }
        $exists = $this->userRepository->findByUserName($payload['email']);

        if (!empty($exists)) {
            Response::json([
                'message' => 'User already registered!'
            ], 422);
        }
        //hash the password

        $hashedPassword = password_hash($payload['password'], PASSWORD_BCRYPT);
        $token = Jwt::generate($payload['email']);

        setcookie('token', $token, [
            'expires' => time() + 3600,
            'httponly' => true,
            'secure' => false,
            'samesite' => 'Lax',
            'path' => '/',
        ]);
        $createUserDTO = new CreateUserDTO(
            userTypeId: (int) 9,
            firstName: $payload['first_name'] ?? null,
            lastName: $payload['last_name'] ?? null,
            phoneNumber: (int) $payload['phone_number'],
            email: $payload['email'],
            username: $payload['email'],
            password: $hashedPassword
        );
        $createdUser = $this->userRepository->createUser($createUserDTO);
        Response::json([
            'message' => 'New User Created!',
            'user' => $createdUser
            // 'token' => $token
        ], 201);
    }
    public function authUser(): void
    {
        // $headers = getallheaders();
        // $header = $headers['Authorization'] ?? '';

        // var_dump(getallheaders());
        // if (!preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
        //     Response::json([
        //         'message' => 'Unauthenticated'
        //     ], 401);
        // }
        $token = $_COOKIE['token'];

        if (!$token) {
            Response::json([
                'message' => 'Unauthorized!'
            ], 401);
        }


        $payload = Jwt::decode($token);

        $username =  $payload->sub;
        $user = $this->userRepository->findByUserName($username);

        if (!$user) {
            Response::json([
                'message' => 'User not found'
            ], 404);
        }

        Response::json([
            'user' => $user
        ]);
    }
}
