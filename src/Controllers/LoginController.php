<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;

class LoginController extends Controller
{
    public function index(): void
    {
        $this->view->pageTitle = 'Login';

        $this->render('login');

        clearErrors();
    }

    public function login(): void
    {
        $data = $_POST;
        $errors = [];
        $redirectTo = '/';

        if (empty($data['email'])) {
            $errors['email'] = 'Invalid e-mail';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'Invalid password';
        }

        $user = (new User())->findOneBy([['email', $data['email']]]);

        if (!$user || !passwordMatches($data['password'], $user->password)) {
            $errors['credentials'] = 'Invalid credentials';
        }

        if (!empty($errors)) {
            $redirectTo = '/login';
            $_SESSION['errors'] = $errors;
        } else {
            login($user);
        }

        $this->redirectTo($redirectTo);

    }

    public function logout(): void
    {
        logout();

        $this->redirectTo('/');
    }
}