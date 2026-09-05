<?php

namespace App\DTOs;

class CreateUserDTO
{
    public function __construct(
        public int $userTypeId,
        public ?string $firstName,
        public ?string $lastName,
        public ?string $username,
        public string $email,
        public int $phoneNumber,
        public string $password,
    ) {}


    
}
