<?php

namespace Controller;

use Repository\UserProductRepository;
use Request\ChangeProductRequest;

class CartController
{
    private UserProductRepository $userProductRepository;
    public function __construct()
    {
        $this->userProductRepository = new UserProductRepository();
    }
    public function addProduct(ChangeProductRequest $request): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }

        $userId = $_SESSION['user_id'];

        $errors = $request->validate();

        if (empty($errors)) {
            $productId = $request->getProductId();
            $quantity = 1;

            $userProduct = $this->userProductRepository->getOneByUserIdProductId($userId,$productId);

            if (empty($userProduct)) {
                $this->userProductRepository->add($userId, $productId, $quantity);
            }
            else {
                $this->userProductRepository->updateQuantityPlus($userId, $productId, $quantity);
            }
        }

        header("Location: /main");
    }

    public function removeProduct(ChangeProductRequest $request): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }
        $userId = $_SESSION['user_id'];

        $errors = $request->validate();

        if (empty($errors)) {

            $productId = $request->getProductId();
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

        header("Location: /main");
    }

    public function getCart(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])){
            header("Location: /login");
        }

        $userId = $_SESSION['user_id'];

        $productsOfCart = $this->userProductRepository->getAllByUserId($userId);

        $totalPrice = $this->getTotalPrice($productsOfCart);

        require_once './../View/cart.php';
    }
    private function getTotalPrice(array $products): int
    {
        $totalPrice = 0;

        if (!empty($products)){
            foreach($products as $product){
                $totalPrice += $product->getProduct()->getPrice()*$product->getQuantity();
            }
        }

        return $totalPrice;
    }
    public function clearCart(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])){
            header("Location: /login");
        }

        $userId = $_SESSION['user_id'];

        $this->userProductRepository->clearByUserId($userId);

        header("Location: /main");
    }
    public function clearProduct(ChangeProductRequest $request): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])){
            header("Location: /login");
        }
        $userId = $_SESSION['user_id'];

        $errors = $request->validate();

        if (empty($errors)){

            $productId = $request->getProductId();

            $this->userProductRepository->clearProductByUserIdProductId($userId,$productId);
        }

        header("Location: /cart");
    }
}