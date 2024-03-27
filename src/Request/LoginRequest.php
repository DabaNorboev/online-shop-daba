<?php

namespace Request;

use Entity\User;
use Repository\UserRepository;

class LoginRequest extends Request
{

    public function __construct(string $method, string $uri, array $headers, array $body)
    {
        parent::__construct($method, $uri, $headers, $body);
    }

    public function getEmail(): string
    {
        return $this->getBody()['email'];
    }
    public function getPassword(): string
    {
        return $this->getBody()['password'];
    }
    public function validate() : array
    {
        $errors = [];

        $userData = $this->body;

        $email = $userData['email'];

        if (isset($userData['email'])) {
            if (!empty($email)) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = 'Некорректный формат электронной почты';
                }
            } else {
                $errors['email'] = 'Это поле не должно быть пустым';
            }
        } else {
            $errors['email'] = 'Это поле не должно быть пустым';
        }
        if (isset($userData['password'])) {
            $password = $userData['password'];
            if (empty($password)) {
                $errors['password'] = 'Это поле не должно быть пустым';
            }
        } else {
            $errors['password'] = 'Это поле не должно быть пустым';
        }

        return $errors;
    }
}