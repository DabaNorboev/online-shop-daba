<?php

class Product
{
    public function getAll(): array
    {
        $pdo = NEW PDO("pgsql:host=db; port=5432; dbname=laravel", 'root', 'root');
        $stmt = $pdo->query("SELECT * FROM products");

        return $stmt->fetchAll();
    }
    public function getOneById($id): array
    {
        $pdo = NEW PDO("pgsql:host=db; port=5432; dbname=laravel", 'root', 'root');
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();
        if (empty($product)) {
            $product = [];
        }
        return $product;
    }
}