<?php
require_once 'Model.php';
class UserProduct extends Model
{
    public function addProduct(string $user_id, string $product_id, string $quantity): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO user_products (user_id,product_id,quantity) VALUES (:user_id, :product_id, :quantity)");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id, 'quantity' => $quantity]);
    }
}