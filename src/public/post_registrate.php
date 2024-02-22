<?php
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['psw'];
$repeatPassword = $_POST['psw-repeat'];

if (empty($name) || empty($email) || empty($password) || empty($repeatPassword)) {
    die('Все поля должны быть заполнены');
}
if (mb_strlen($name, 'UTF-8') < 2) {
    die('Минимально допустимая длина имени - 2 символа');
}
if (mb_strlen($password, 'UTF-8') < 8) {
    die('Минимально допустимая длина пароля - 8 символов');
}
if (strpos($email, '@') === false || strpos($email, '.') === false) {
    die('Некорректный формат электронной почты');
}
if ($password !== $repeatPassword) {
    die('Пароли не совпадают');
}

$pdo = NEW PDO("pgsql:host=db; port=5432; dbname=laravel", 'root', 'root');

$statement = $pdo->prepare("INSERT INTO users (name,email,password) VALUES (:name, :email, :password)");
$statement->execute(['name' => $name, 'email' => $email, 'password' => $password]);

$statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$statement->execute(['email' => $email]);

$result = $statement->fetch();

print_r($result);