<?php

namespace Controller;

use Repository\UserRepository;
use Request\LoginRequest;
use Request\RegistrationRequest;
use Service\AuthenticationService;

class UserController
{
    private UserRepository $userRepository;
    private AuthenticationService $authenticationService;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->authenticationService = new AuthenticationService();
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

            $this->authenticationService->login($email);

            header('Location: /main');
        }
        require_once './../View/login.php';
    }
    public function logout(): void
    {
        $this->authenticationService->logout();
        header('Location: /login');
    }
}
