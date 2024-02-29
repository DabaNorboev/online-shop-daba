<?php
function validate(array $array, PDO $pdo): array
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
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
                    $stmt->execute(['email' => $email]);
                    $result = $stmt->fetch();
                    if (!empty($result)) {
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
    return [$errors, $values];
}

$pdo = NEW PDO("pgsql:host=db; port=5432; dbname=laravel", 'root', 'root');
[$errors, $values] = validate($_POST, $pdo);

if (empty($errors)) {
    [$name, $email, $password,$repeatPassword] = array_values($values);
    $name = $values['name'];
    $email = $values['email'];
    $password = $values['psw'];
    $repeatPassword = $values['psw-repeat'];

    $password = password_hash($password,PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name,email,password) VALUES (:name, :email, :password)");
    $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);
    header('Location: ./login.php');
}
require_once './registrate.php';
