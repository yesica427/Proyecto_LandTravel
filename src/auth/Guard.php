<?php

namespace App\Auth;

final class Guard
{
    private $key;

    public function __construct(string $jwt)
    {
        $this->key = $jwt;
    }

    public function protect(callable $middlware) : ProtectedRoute
    {
        return new ProtectedRoute($this->key, $middlware);
    }
}