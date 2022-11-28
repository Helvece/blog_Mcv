<?php

namespace App\Interfaces;

interface IPasswordGenerator
{
    /**
     * Generate a bcrypt password hash
     * @param int $password the password to hash
     * @return string the hashed password
     */
    public function generatePassword(string $password): string;

    /**
     * Verify a password against a hash
     * @param string $password the password to verify
     * @param string $hash the hash to verify against
     * @return bool true if the password matches the hash, false otherwise
     */
    public function verifyPassword(string $password, string $hash): bool;
}