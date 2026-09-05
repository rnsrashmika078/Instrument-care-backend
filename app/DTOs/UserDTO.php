<?php

namespace App\DTOs;



class UserDTO
{
    public function __construct(
        public int $id,
        public int $userTypeId,
        public ?string $firstName,
        public ?string $lastName,
        public string $username,
    ) {}
    public static function fromArray(array $user): self
    {
        return new self(
            id: (int) $user['id'],
            userTypeId: (int) $user['user_type_id'],
            firstName: $user['first_name'],
            lastName: $user['last_name'],
            username: $user['username'],
        );
    }
    public function toArray(): array
    {
        return [
            'userTypeId' => $this->userTypeId,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'username' => $this->username,
        ];
    }
}
