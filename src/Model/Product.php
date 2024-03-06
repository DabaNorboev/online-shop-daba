<?php
require_once 'Model.php';
class Product extends Model
{
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM products");

        return $stmt->fetchAll();
    }
    public function getOneById($id): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();
        if (empty($product)) {
            $product = [];
        }
        return $product;
    }
}