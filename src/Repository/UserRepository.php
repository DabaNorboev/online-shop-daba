<?php

namespace Repository;

use Entity\User;

class UserRepository extends Repository
{
    public function getUserByEmail(string $email): User | null
    {
        $stmt = self::getPdo()->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch();

        if (empty($user)) {
            return null;
        }
         return $this->hydrate($user);
    }

    public function getUserById(int $userId): User | null
    {
        $stmt = self::getPdo()->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);

        $user = $stmt->fetch();

        if (empty($user)) {
            return null;
        }
        return $this->hydrate($user);
    }

     public function create(string $name, string $email, string $password): void
     {
         $stmt = self::getPdo()->prepare("INSERT INTO users (name,email,password) VALUES (:name, :email, :password)");
         $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);
     }

    private function hydrate(array $user): User
    {
        return new User($user['id'],$user['name'],$user['email'],$user['password']);
    }
}