<?php

namespace Controller;

use Psr\Log\LoggerInterface;
use Repository\UserRepository;
use Request\LoginRequest;
use Request\RegistrationRequest;
use Service\Authentication\AuthenticationServiceInterface;
class UserController
{
    private UserRepository $userRepository;
    private AuthenticationServiceInterface $authenticationService;
    private LoggerInterface $logger;

    public function __construct(AuthenticationServiceInterface $authenticationService, UserRepository $userRepository, LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->authenticationService = $authenticationService;
        $this->logger = $logger;
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

            $userId = $this->userRepository->getUserByEmail($email)->getId();

            $data = [
                'user-id' => "user_id: " . $userId,
                'name' => "name: " . $name,
                'email' => "email: " . $email
            ];

            $this->logger->info("User is registered\n", $data);

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
