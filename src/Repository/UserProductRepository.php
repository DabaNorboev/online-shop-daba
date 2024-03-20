<?php

namespace Repository;

use Entity\CartProduct;
use Entity\Product;
use Entity\User;
use Entity\UserProduct;

class UserProductRepository extends Repository
{
    public function add(string $userId, string $productId, string $quantity): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO user_products (user_id,product_id,quantity) VALUES (:user_id, :product_id, :quantity)");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId, 'quantity' => $quantity]);
    }

    public function getOneByUserIdProductId($userId, $productId): UserProduct | null
    {
        $stmt = $this->pdo->prepare("SELECT up.id AS id, u.id AS user_id, u.name AS user_name, u.email, u.password, 
        p.id AS product_id, p.name AS product_name, p.price, p.description, p.img_url, up.quantity FROM user_products up
        JOIN users u ON up.user_id = u.id
        JOIN products p ON up.product_id = p.id
        WHERE u.id = :user_id AND p.id = :product_id");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);

        $userProduct = $stmt->fetch();

        if (empty($userProduct)) {
            return null;
        }

        return $this->hydrate($userProduct);
    }

    public function getAllByUserId($userId): array
    {
        $stmt = $this->pdo->prepare("SELECT up.id AS id, u.id AS user_id, u.name AS user_name, u.email, u.password, 
        p.id AS product_id, p.name AS product_name, p.price, p.description, p.img_url, up.quantity FROM user_products up
        JOIN users u ON up.user_id = u.id
        JOIN products p ON up.product_id = p.id
        WHERE u.id = :user_id;");

        $stmt->execute(['user_id' => $userId]);

        $userProducts = $stmt->fetchAll();

        if (empty($userProducts)){
            return [];
        }

        $userProductsArray = [];

        foreach ($userProducts as $userProduct){

            $userProductsArray[] = $this->hydrate($userProduct);
        }
        return $userProductsArray;
    }

    public function remove(string $userId, string $productId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM user_products WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
    }

    public function updateQuantityPlus(string $userId, string $productId, string $quantity): void
    {
        $stmt = $this->pdo->prepare("UPDATE user_products SET quantity = quantity + :quantity WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId, 'quantity' => $quantity]);
    }

    public function updateQuantityMinus(string $userId, string $productId, string $quantity): void
    {
        $stmt = $this->pdo->prepare("UPDATE user_products SET quantity = quantity - :quantity WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId, 'quantity' => $quantity]);
    }

    public function clearByUserId(string $userId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM user_products WHERE user_id=:user_id");
        $stmt->execute(['user_id' => $userId]);
    }

    public function clearProductByUserIdProductId(string $userId, string $productId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM user_products WHERE user_id=:user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
    }
    
    private function hydrate(array $userProduct): UserProduct
    {
        return new UserProduct($userProduct['id'],
            new User($userProduct['user_id'],$userProduct['user_name'],$userProduct['email'],$userProduct['password']),
            new Product($userProduct['product_id'],$userProduct['product_name'],$userProduct['description'],$userProduct['price'],$userProduct['img_url']),
            $userProduct['quantity']);
    }
}