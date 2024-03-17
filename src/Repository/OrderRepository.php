<?php

namespace Repository;

class OrderRepository extends Repository
{
    public function create(string $userId,string $name, string $phoneNumber, string $address, string $comment): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO orders (user_id,user_name,phone_number,address,comment) VALUES (:user_id, :name, :phone_number, :address, :comment)");
        $stmt->execute(['user_id' => $userId, 'name' => $name, 'phone_number' => $phoneNumber, 'address' => $address, 'comment' => $comment]);
    }
    public function getOrderId(): string
    {
        return $this->pdo->lastInsertId();
    }
}