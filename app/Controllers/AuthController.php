<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function login()
    {
        return redirect()->route('login');
    }

    public function callback()
    {
        return redirect()->route('login');
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->route('login');
    }

    public function perfil()
    {
        if (! auth()->loggedIn()) {
            return redirect()->route('login');
        }

        return $this->render('auth/perfil', [
            'user' => auth()->user(),
        ]);
    }
}