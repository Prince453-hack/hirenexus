<?php

namespace Framework\Middleware;

use Framework\Session;

class Authorize
{
    public function isAuthenticated()
    {
        return Session::has('user');
    }

    public function handle($role)
    {
        if ($role === "guest" && $this->isAuthenticated()) {
            return header("Location: /");
            exit;
        } elseif ($role === "auth" && !$this->isAuthenticated()) {
            return header("Location: /auth/login");
            exit;
        }
    }
}
