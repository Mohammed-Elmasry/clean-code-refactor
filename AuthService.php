<?php

class AuthService
{
    private $authenticatedUser;

    public function __construct()
    {
        $this->authenticatedUser = null;
    }

    public function authenticate($username, $password)
    {
        if ($username === "user" && $password === "pass") {
            $this->authenticatedUser = $username;
            echo "User authenticated\n";
            return true;
        }
        echo "Authentication failed\n";
        return false;
    }

    public function isAuthenticated()
    {
        return $this->authenticatedUser !== null;
    }
}