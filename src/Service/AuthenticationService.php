<?php

namespace Service;

use Entity\User;
use Repository\UserRepository;

class AuthenticationService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function check(): bool
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user_id']);
    }

    public function getCurrentUser(): User | null
    {
        if ($this->check()){
            $userId = $_SESSION['user_id'];

            return $this->userRepository->getUserById($userId);
        }

        return null;
    }

    public function login(string $email): void
    {
        if (!$this->check()) {
            $user = $this->userRepository->getUserByEmail($email);

            $_SESSION['user_id'] = $user->getId();
        }
    }

    public function logout(): void
    {
        if ($this->check()){
            unset($_SESSION['user_id']);
            session_unset();
            session_destroy();
        }
    }


}