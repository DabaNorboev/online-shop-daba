<?php
function validate(array $array): array
{
    $errors = [];
    $values = [];

    foreach ($array as $key => $value) {
        if (isset($value)) {
            $values[$key] = $value;
            if (empty($value)) {
                $errors[$key] = "Это поле не должно быть пустым";
            }
        }elseif ($key === 'email') {
            $email = $value;
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[$key] = 'Некорректный формат электронной почты';
            }
        }elseif ($key === 'password') {
            if (empty($value)) {
                $errors[$key] = 'Поле пароля не должно быть пустым';
            }
        }else {
            $errors[$key] = "Это поле не должно быть пустым";
        }
    }
    return [$errors, $values];
}

[$errors, $values] = validate($_POST);

if (empty($errors)) {
    [$email, $password] = array_values($values);

    $pdo = NEW PDO("pgsql:host=db; port=5432; dbname=laravel", 'root', 'root');
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->execute(['email' => $email]);

    $user = $stmt->fetch();

    if (empty($user)) {
        $errors['email'] = 'неправильный email или пароль';
    } else {
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            header('Location: ./main.php');
        } else {
            $errors['email'] = 'неправильный email или пароль';
        }
    }
}
