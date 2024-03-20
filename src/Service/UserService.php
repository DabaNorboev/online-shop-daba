<?php

namespace Service;

use Entity\User;
use Repository\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function check(): bool
    {
        session_start();

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
}