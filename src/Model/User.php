<?php
class User
{
    public function getUserByEmail(string $email) :array
    {
        $pdo = new PDO("pgsql:host=db;port=5432;dbname=laravel", "root", "root");

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch();
        if (empty($user)) {
            $user = [];
        }
        return $user;
    }
     public function create(string $name, string $email, string $password): void
     {
         $pdo = NEW PDO("pgsql:host=db; port=5432; dbname=laravel", 'root', 'root');
         $stmt = $pdo->prepare("INSERT INTO users (name,email,password) VALUES (:name, :email, :password)");
         $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);
     }
}