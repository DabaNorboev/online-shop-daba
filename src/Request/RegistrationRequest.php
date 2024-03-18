<?php

namespace Request;

use Repository\UserRepository;

class RegistrationRequest extends Request
{
    private UserRepository $userRepository;
    public function __construct(string $method, string $uri, array $headers, array $body)
    {
        parent::__construct($method, $uri, $headers, $body);

        $this->userRepository = new UserRepository();
    }
    public function getName()
    {
        return $this->getBody()['name'];
    }

    public function getEmail()
    {
        return $this->getBody()['email'];
    }

    public function getPassword()
    {
        return $this->getBody()['psw'];
    }

    public function validate(): array
    {
        $errors = [];
        $userData = $this->body;

        $email = $userData['email'];
        $password = $userData['psw'];

        $user = $this->userRepository->getUserByEmail($email);

        foreach ($userData as $key=> $value)
        {
            if (isset($value)){
                if (empty($value)) {
                    $errors[$key] = "Это поле не должно быть пустым";
                }elseif($key === 'name') {
                    if (mb_strlen($value, 'UTF-8') < 2) {
                        $errors['name'] = 'Минимально допустимая длина имени - 2 символа';
                    }
                }elseif ($key === 'email') {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        if (!empty($user)) {
                            $errors['email'] = 'Пользователь с таким email уже существует';
                        }
                    }else {
                        $errors['email'] = 'Некорректный формат электронной почты';
                    }
                }elseif ($key === 'psw') {
                    if (mb_strlen($value, 'UTF-8') < 8) {
                        $errors['psw'] = 'Минимально допустимая длина пароля - 8 символов';
                    }elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/', $value)) {
                        $errors['psw'] = 'Пароль должен содержать минимум одну заглавную букву, одну строчную букву и одну цифру';
                    }
                }elseif ($key === 'psw-repeat') {
                    if ($value !== $password) {
                        $errors['psw-repeat'] = 'Пароли не совпадают';
                    }
                }
            }else {
                $errors[$key] = "Это поле не должно быть пустым";
            }
        }
        return $errors;
    }
}