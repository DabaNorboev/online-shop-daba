<?php

namespace Controller;

use Model\User;

class UserController
{
    private User $userModel;
    public function __construct()
    {
        $this->userModel = new User();
    }

    public function getRegistrate(): void
    {
        require_once './../View/registrate.php';
    }

    public function postRegistrate(array $data): void
    {
        $errors = $this->validateRegistrate($data);

        if (empty($errors)) {
            $name = $data['name'];
            $email = $data['email'];
            $password = $data['psw'];

            $password = password_hash($password,PASSWORD_DEFAULT);

            $this->userModel->create($name, $email, $password);

            header('Location: login');
        }

        require_once './../View/registrate.php';
    }

    private function validateRegistrate(array $userData): array
    {
        $errors = [];
        $email = $userData['email'];
        $password = $userData['psw'];
        $user = $this->userModel->getUserByEmail($email);
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

    public function getLogin (): void
    {
        require_once './../View/login.php';
    }

    public function postLogin(array $data): void
    {
        $errors = $this->validateLogin($data);

        if (empty($errors)) {
            $email = $data["email"];

            $user = $this->userModel->getUserByEmail($email);

            if (empty($user)) {
                $errors['email'] = 'неправильный email или пароль';
            } else {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                header('Location: main');
            }
        }
        require_once './../View/login.php';
    }

    private function validateLogin(array $userData) : array
    {
        $errors = [];
        $email = $userData['email'];
        $user = $this->userModel->getUserByEmail($email);
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
                } elseif (!password_verify($password, $user['password'])) {
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
    public function logout(): void
    {
        session_start();
        if (isset($_SESSION['user_id'])){
            unset($_SESSION['user_id']);
            session_unset();
            session_destroy();
        }
        header("Location: /login");
    }
}
