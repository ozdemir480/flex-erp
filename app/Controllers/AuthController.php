<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

class AuthController
{
    public function loginForm(Request $request): Response
    {
        return new Response('<form method="POST" action="/auth/login"><input name="email"><input name="password" type="password"><button>Login</button></form>', 200, ['Content-Type'=>'text/html']);
    }

    public function login(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['user'] = $request->input('email');
        return new Response('ok');
    }

    public function logout(Request $request): Response
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        session_destroy();
        return new Response('bye');
    }
}
