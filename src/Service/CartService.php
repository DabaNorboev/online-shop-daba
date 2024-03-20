<?php

namespace Service;

use Repository\UserProductRepository;
use Request\ChangeProductRequest;

class CartService
{
    private UserProductRepository $userProductRepository;

    public function __construct()
    {
        $this->userProductRepository = new UserProductRepository();
    }

    public function getTotalPrice(array $userProducts): int
    {
        $totalPrice = 0;

        if (!empty($userProducts)){

            foreach($userProducts as $userProduct){
                $price = $userProduct->getProduct()->getPrice();

                $totalPrice += $price * $userProduct->getQuantity();
            }
        }

        return $totalPrice;
    }
    public function addProduct(int $productId, int $userId): void
    {
        $quantity = 1;

        $userProduct = $this->userProductRepository->getOneByUserIdProductId($userId,$productId);

        if (empty($userProduct)) {
            $this->userProductRepository->add($userId, $productId, $quantity);
        }
        else {
            $this->userProductRepository->updateQuantityPlus($userId, $productId, $quantity);
        }
    }

    public function removeProduct(int $productId, int $userId): void
    {
        $quantity = 1;

        $userProduct = $this->userProductRepository->getOneByUserIdProductId($userId,$productId);

        if (!empty($userProduct)) {
            if ($userProduct->getQuantity() === 1) {
                $this->userProductRepository->remove($userId, $productId);
            } elseif ($userProduct->getQuantity() !== 0){
                $this->userProductRepository->updateQuantityMinus($userId, $productId, $quantity);
            }
        }
    }

    public function getCartCount(array $products): int
    {
        $count = 0;
        foreach ($products as $product) {
            $count += $product->getQuantity();
        }

        return $count;
    }
}