<?php

namespace Controller;

use Repository\UserRepository;
use Request\LoginRequest;
use Request\RegistrationRequest;
use Service\Authentication\AuthenticationServiceInterface;
use Service\Authentication\AuthenticationSessionService;

class UserController
{
    private UserRepository $userRepository;
    private AuthenticationServiceInterface $authenticationService;

    public function __construct(AuthenticationServiceInterface $authenticationService)
    {
        $this->userRepository = new UserRepository();
        $this->authenticationService = $authenticationService;
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

            if ($this->authenticationService->login($email,$request->getPassword())){
                header('Location: /main');
            }
            else {
                $errors['password'] = 'неправильный email или пароль';
            }
        }
        require_once './../View/login.php';
    }
    public function logout(): void
    {
        $this->authenticationService->logout();
        header('Location: /login');
    }
}
