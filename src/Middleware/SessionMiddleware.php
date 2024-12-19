<?php

namespace App\Middleware;

class SessionMiddleware
{
    public function handle()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
} 