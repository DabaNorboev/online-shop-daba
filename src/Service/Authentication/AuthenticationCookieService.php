<?php

namespace Service\Authentication;

use Entity\User;
use Psr\Log\LoggerInterface;
use Repository\UserRepository;

class AuthenticationCookieService implements AuthenticationServiceInterface
{
    private UserRepository $userRepository;

    private LoggerInterface $logger;

    public function __construct(UserRepository $userRepository, LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->logger = $logger;
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
            $userId = $user->getId();

            setcookie('user_id', $userId, time() + 3600, '/');

            $cookieId = $_COOKIE['PHPSESSID'];

            $data = [
                'email' => 'email: ' . $email,
                'User_Id' => 'user_id: ' . $userId,
                'cookie' => 'cookie: ' . $cookieId
            ];

            $this->logger->info("Authentication successful\n", $data);

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
