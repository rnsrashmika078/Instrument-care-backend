<?php

namespace App\Security;

use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;


class Jwt
{
    private const ALOGIRITH = 'HS256';

    public static function generate(string $username): string
    {
        $secret = $_ENV['JWT_SECRET'];

        $issuedAt = time();
        $expiration = $issuedAt + 3600; // up to an hour

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expiration,
            'sub' => $username
        ];

        return FirebaseJWT::encode($payload, $secret, self::ALOGIRITH);
    }

    public static function decode(string $token): object
    {
        $secret = $_ENV['JWT_SECRET'];
        return FirebaseJWT::decode($token, new Key($secret, self::ALOGIRITH));
    }
}
