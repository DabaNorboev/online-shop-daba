<?php

namespace Request;

use Entity\User;
use Repository\UserRepository;

class LoginRequest extends Request
{
    private UserRepository $userRepository;

    public function __construct(string $method, string $uri, array $headers, array $body)
    {
        parent::__construct($method, $uri, $headers, $body);
        $this->userRepository = new UserRepository();
    }

    public function getEmail()
    {
        return $this->getBody()['email'];
    }
    public function validate() : array
    {
        $errors = [];

        $userData = $this->body;

        $email = $userData['email'];

        $user = $this->userRepository->getUserByEmail($email);

        if (isset($userData['email'])) {
            if (!empty($email)) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = 'Некорректный формат электронной почты';
                } elseif (empty($user)) {
                    $errors['email'] = 'неправильный email или пароль';
                }
            } else {
                $errors['email'] = 'Это поле не должно быть пустым';
            }
        } else {
            $errors['email'] = 'Это поле не должно быть пустым';
        }

        if (isset($userData['password'])) {
            $password = $userData['password'];
            if (!empty($password)) {

                if (empty($user)) {
                    $errors['email'] = 'неправильный email или пароль';
                } elseif (!password_verify($password, $user->getPassword())) {
                    $errors['psw'] = 'неправильный email или пароль';
                }
            } else {
                $errors['psw'] = 'Это поле не должно быть пустым';
            }
        } else {
            $errors['psw'] = 'Это поле не должно быть пустым';
        }

        return $errors;
    }
}