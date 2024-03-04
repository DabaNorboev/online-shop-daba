<?php
class UserController
{
    public function getRegistrate(): void
    {
        require_once './../View/registrate.php';
    }

    public function postRegistrate(): void
    {
        $errors = $this->validateRegistrate($_POST)['errors'];
        $values = $this->validateRegistrate($_POST)['values'];

        if (empty($errors)) {
            $name = $values['name'];
            $email = $values['email'];
            $password = $values['psw'];

            $password = password_hash($password,PASSWORD_DEFAULT);

            $userModel = new User();
            $userModel->create($name, $email, $password);
            header('Location: login');
        }

        require_once './../View/registrate.php';
    }

    private function validateRegistrate(array $array): array
    {
        $errors = [];
        $values = [];

        foreach ($array as $key=>$value)
        {
            if (isset($value)){
                $values[$key] = $value;
                if (empty($value)) {
                    $errors[$key] = "Это поле не должно быть пустым";
                }elseif($key === 'name') {
                    if (mb_strlen($value, 'UTF-8') < 2) {
                        $errors['name'] = 'Минимально допустимая длина имени - 2 символа';
                    }
                }elseif ($key === 'email') {
                    $email = $value;
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $userModel = new User();
                        $user = $userModel->getUserByEmail($email);
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
                    $password = $value;
                }elseif ($key === 'psw-repeat') {
                    if ($value !== $password) {
                        $errors['psw-repeat'] = 'Пароли не совпадают';
                    }
                }
            }else {
                $errors[$key] = "Это поле не должно быть пустым";
            }
        }
        return ['errors' => $errors, 'values' => $values];
    }

    public function getLogin (): void
    {
        require_once './../View/login.php';
    }

    public function postLogin(): void
    {
        $errors = $this->validateLogin($_POST)['errors'];
        $values = $this->validateLogin($_POST)['values'];

        if (empty($errors)) {
            $email = $values["email"];

            $userModel = new User();
            $user = $userModel->getUserByEmail($email);

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

    private function validateLogin(array $array) : array
    {
        $errors = [];
        $values = [];

        if (isset($array['email'])) {
            $email = $array['email'];
            $values['email'] = $email;
            if (!empty($email)) {
                $userModel = new User();
                $user = $userModel->getUserByEmail($email);
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

        if (isset($array['password'])) {
            $password = $array['password'];
            $values['password'] = $password;
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
        return ['errors' => $errors, 'values' => $values];
    }

    public function checkSession(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }
    }
}
require_once './../Model/User.php';
