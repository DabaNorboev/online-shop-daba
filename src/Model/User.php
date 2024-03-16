<?php

namespace Model;

use Entity\UserEntity;

class User extends Model
{
    public function getUserByEmail(string $email): UserEntity | null
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch();

        if (empty($user)) {
            return null;
        }
         return new UserEntity($user['id'],$user['name'],$user['email'],$user['password']);
    }
     public function create(string $name, string $email, string $password): void
     {
         $stmt = $this->pdo->prepare("INSERT INTO users (name,email,password) VALUES (:name, :email, :password)");
         $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);
     }
}