<?php

namespace Service\Authentication;

use Entity\User;
use Repository\UserRepository;

class AuthenticationServiceSession
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

    public function login(string $email, string $password): bool
    {
        $user = $this->userRepository->getUserByEmail($email);

        if (!$user instanceof User){
            return false;
        }

        if (password_verify($user->getPassword(), $password)){
            session_start();
            $_SESSION['user_id'] = $user->getId();
            return true;
        }

        return false;
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