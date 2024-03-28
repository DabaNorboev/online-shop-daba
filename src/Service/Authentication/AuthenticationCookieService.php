<?php

namespace Service\Authentication;

use Entity\User;
use Repository\UserRepository;

class AuthenticationCookieService implements AuthenticationServiceInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function check(): bool
    {
        return isset($_COOKIE['user_id']);
    }

    public function getCurrentUser(): User | null
    {
        if ($this->check()){
            $userId = $_COOKIE['user_id'];

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

        if (password_verify($password, $user->getPassword())){

            setcookie('user_id', $user->getId(), time() + 3600, '/');
            return true;
        }

        return false;
    }

    public function logout(): void
    {
        if ($this->check()){
            // Удаляем куку пользователя
            setcookie('user_id', '', time() - 3600, '/');
        }
    }
}
