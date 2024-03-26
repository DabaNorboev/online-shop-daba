<?php

namespace Repository;

class OrderProductRepository extends Repository
{
    public function add(string $orderId, string $productId, string $quantity): void
    {
        $stmt = self::getPdo()->prepare("INSERT INTO order_products (order_id,product_id,quantity) VALUES (:order_id, :product_id, :quantity)");
        $stmt->execute(['order_id' => $orderId, 'product_id' => $productId, 'quantity' => $quantity]);
    }
}