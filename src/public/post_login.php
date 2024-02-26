<?php
$errors = [];
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if (empty($email)) {
        $errors['email'] = 'Поле электронной почты не должно быть пустым';
    }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный формат электронной почты';
    }
}else {
    $errors['email'] = 'Поле электронной почты должно быть заполнено';
}
if (isset($_POST['password'])) {
    $password = $_POST['password'];
    if (empty($password)) {
        $errors['password'] = 'Поле пароля не должно быть пустым';
    }
}else {
    $errors['password'] = 'Поле пароля должно быть заполнено';
}

if (empty($errors)) {
    $pdo = NEW PDO("pgsql:host=db; port=5432; dbname=laravel", 'root', 'root');

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->execute(['email' => $email]);

    $user = $stmt->fetch();
    if (empty($user)) {
        $errors['email'] = 'неправильный логин или пароль';
    } else {
        if (password_verify($password, $user['password'])) {
            setcookie('user_id', $user['id']);
            echo ' verify success';
        } else {
            $errors['email'] = 'неправильный логин или пароль';
        }
    }
}
