<?php

namespace App\Helper;

class TokenHelper
{
    public function createToken():string
    {
        //generate random 32 character string while it doesn't exist in database
        $token = bin2hex(random_bytes(16));
        return $token; //bin2hex(random_bytes(16));
    }
}