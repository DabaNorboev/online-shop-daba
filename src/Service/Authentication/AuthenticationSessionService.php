<?php

namespace Service\Authentication;

use Entity\User;
use Psr\Log\LoggerInterface;
use Repository\UserRepository;

class AuthenticationSessionService implements AuthenticationServiceInterface
{

    private LoggerInterface $logger;
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository, LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }

    public function check(): bool
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user_id']);
    }

    public function getCurrentUser(): User|null
    {
        if ($this->check()) {
            $userId = $_SESSION['user_id'];

            return $this->userRepository->getUserById($userId);
        }

        return null;
    }

    public function login(string $email, string $password): bool
    {
        $user = $this->userRepository->getUserByEmail($email);

        if (!$user instanceof User) {
            return false;
        }

        if (password_verify($password ,$user->getPassword())) {
            session_start();
            $userId = $user->getId();
            $_SESSION['user_id'] = $userId;
            $sessionId = $_COOKIE['PHPSESSID'];

            $data = [
                'email' => 'email: ' . $email,
                'Id' => 'Id: ' . $userId,
                'Session' => 'Session: ' . $sessionId
            ];

            $this->logger->info("Успешная авторизация пользователя", $data);

            return true;
        }

        return false;
    }

    public function logout(): void
    {
        if ($this->check()) {
            unset($_SESSION['user_id']);
            session_unset();
            session_destroy();
        }
    }

}