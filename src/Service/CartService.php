<?php

namespace Service;

use Repository\UserProductRepository;

class CartService
{
    private UserProductRepository $userProductRepository;

    public function __construct()
    {
        $this->userProductRepository = new UserProductRepository();
    }

    public function getTotalPrice(int $userId): int
    {
        $userProducts = $this->getCartProducts($userId);

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

    public function getCartProducts(int $userId): array
    {
        return $this->userProductRepository->getAllByUserId($userId);
    }

    public function clearCartByUserId(int $userId): void
    {
        $this->userProductRepository->clearByUserId($userId);
    }

    public function clearProductByUserIdProductId(int $userId, $productId): void
    {
        $this->userProductRepository->clearProductByUserIdProductId($userId,$productId);
    }
}