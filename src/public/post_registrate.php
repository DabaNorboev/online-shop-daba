<?php
$errors = [];

if (isset($_POST['name'])) {
    $name = $_POST['name'];
    if (empty($name)) {
        $errors['name'] = 'Поле имени не должно быть пустым';
    } elseif (mb_strlen($name, 'UTF-8') < 2) {
        $errors['name'] = 'Минимально допустимая длина имени - 2 символа';
    }
}else {
    $errors['name'] = 'Поле имени должно быть заполнено';
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    if (empty($email)) {
        $errors['email'] = 'Поле электронной почты не должно быть пустым';
    }elseif (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $pdo = NEW PDO("pgsql:host=db; port=5432; dbname=laravel", 'root', 'root');
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        $result = $stmt->fetch();
        if (!empty($result)) {
            $errors['email'] = 'Пользователь с таким email уже существует';
        }
    }else {
        $errors['email'] = 'Некорректный формат электронной почты';
    }
}else {
    $errors['email'] = 'Поле электронной почты должно быть заполнено';
}

if (isset($_POST['psw'])) {
    $password = $_POST['psw'];
    if (empty($password)) {
        $errors['psw'] = 'Поле пароля не должно быть пустым';
    }elseif (mb_strlen($password, 'UTF-8') < 8) {
        $errors['psw'] = 'Минимально допустимая длина пароля - 8 символов';
    }elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
        $errors['psw'] = 'Пароль должен содержать минимум одну заглавную букву, одну строчную букву и одну цифру';
    }
}else {
    $errors['psw'] = 'Поле пароля должно быть заполнено';
}

if (isset($_POST['psw-repeat'])) {
    $repeatPassword = $_POST['psw-repeat'];
    if (isset($password)) {
        if ($password !== $repeatPassword) {
            $errors['psw-repeat'] = 'Пароли не совпадают';
        }
    }
} else {
    $errors['psw-repeat'] = 'Поле повтора пароля должно быть заполнено';
}



if (empty($errors)) {
    $password = password_hash($password,PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name,email,password) VALUES (:name, :email, :password)");
    $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);

}
require_once './registrate.php';
