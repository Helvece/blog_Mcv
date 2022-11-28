<?php

namespace App\Helper;

class PasswordHelper
{
    public static function hashPassword(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_BCRYPT);
    }

    /**
     * Verify a password against a hash
     * @param string $password the password to verify
     * @param string $hash the hash to verify against
     * @return bool true if the password matches the hash, false otherwise
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}