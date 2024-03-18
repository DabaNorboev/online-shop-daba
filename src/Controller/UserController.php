<?php

namespace Controller;

use Repository\UserRepository;
use Request\LoginRequest;
use Request\RegistrationRequest;

class UserController
{
    private UserRepository $userRepository;
    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function getRegistration(): void
    {
        require_once './../View/registration.php';
    }

    public function postRegistration(RegistrationRequest $request): void
    {
        $errors = $request->validate();

        if (empty($errors)) {
            $name = $request->getName();
            $email = $request->getEmail();
            $password = $request->getPassword();

            $password = password_hash($password,PASSWORD_DEFAULT);

            $this->userRepository->create($name, $email, $password);

            header('Location: /login');
        }

        require_once './../View/registration.php';
    }

    public function getLogin (): void
    {
        require_once './../View/login.php';
    }

    public function postLogin(LoginRequest $request): void
    {

        $errors = $request->validate();

        if (empty($errors)) {
            $email = $request->getEmail();

            $user = $this->userRepository->getUserByEmail($email);

            if (empty($user)) {
                $errors['email'] = 'неправильный email или пароль';
            } else {
                session_start();
                $_SESSION['user_id'] = $user->getId();
                header('Location: /main');
            }
        }
        require_once './../View/login.php';
    }
    public function logout(): void
    {
        session_start();
        if (isset($_SESSION['user_id'])){
            unset($_SESSION['user_id']);
            session_unset();
            session_destroy();
        }
        header("Location: /login");
    }
}
