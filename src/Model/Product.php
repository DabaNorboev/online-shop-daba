<?php

namespace Model;

use Entity\ProductEntity;
class Product extends Model
{
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM products");

        $products = $stmt->fetchAll();

        if (empty($products)){
            return [];
        }
        $productsArray = [];
        foreach ($products as $product){
            $productsArray[] = new ProductEntity($product['id'],$product['name'],$product['description'],$product['price'],$product['img_url']);
        }
        return $productsArray;
    }
    public function getOneById($id): ProductEntity | null
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();
        if (empty($product)) {
            return null;
        }
        return new ProductEntity($product['id'],$product['name'],$product['description'],$product['price'],$product['img_url']);
    }
}