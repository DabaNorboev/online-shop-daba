<?php

namespace Repository;

use Entity\Product;
class ProductRepository extends Repository
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
            $productsArray[] = $this->hydrate($product);
        }
        return $productsArray;
    }
    public function getOneById($id): Product | null
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();
        if (empty($product)) {
            return null;
        }
        return $this->hydrate($product);
    }

    private function hydrate(array $product): Product
    {
        return new Product($product['id'],$product['name'],$product['description'],$product['price'],$product['img_url']);
    }
}