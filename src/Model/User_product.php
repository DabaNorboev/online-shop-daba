<?php

class User_product
{
    public function addProduct(string $user_id, string $product_id, string $quantity): void
    {
        $pdo = NEW PDO("pgsql:host=db; port=5432; dbname=laravel", 'root', 'root');
        $stmt = $pdo->prepare("INSERT INTO user_products (user_id,product_id,quantity) VALUES (:user_id, :product_id, :quantity)");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id, 'quantity' => $quantity]);
    }
}