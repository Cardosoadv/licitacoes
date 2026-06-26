<?php

namespace App\Services;

class AuthService
{
    public function __construct(...$args)
    {
        // Shield handles session-based authentication.
    }

    public function getAuthenticatedUser()
    {
        return auth()->user();
    }

    public function logout()
    {
        auth()->logout();
    }

    public function isLoggedIn(): bool
    {
        return auth()->loggedIn();
    }

    public function requireAuth(): void
    {
        if (! $this->isLoggedIn()) {
            throw new \Exception('Authentication required');
        }
    }
}