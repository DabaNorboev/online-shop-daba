<?php

namespace Service;

use Entity\User;
use Entity\UserProduct;
use Repository\UserProductRepository;
use Service\Authentication\AuthenticationServiceInterface;

class CartService
{
    private UserProductRepository $userProductRepository;

    private AuthenticationServiceInterface $authenticationService;


    public function __construct(AuthenticationServiceInterface $authenticationService, UserProductRepository $userProductRepository)
    {
        $this->userProductRepository = $userProductRepository;
        $this->authenticationService = $authenticationService;
    }

    public function getTotalPrice(): int
    {
        $user = $this->authenticationService->getCurrentUser();
        if (!$user instanceof User){
            return 0;
        }
        $userProducts = $this->getProducts();

        $totalPrice = 0;

        if (!empty($userProducts)){

            foreach($userProducts as $userProduct){
                $price = $userProduct->getProduct()->getPrice();

                $totalPrice += $price * $userProduct->getQuantity();
            }
        }

        return $totalPrice;
    }
    public function addProduct(int $productId): void
    {
        $user = $this->authenticationService->getCurrentUser();

        if (!$user instanceof User){
            return;
        }

        $userId = $user->getId();

        $userProduct = $this->userProductRepository->getOneByUserIdProductId($userId,$productId);

        if (empty($userProduct)) {
            $this->userProductRepository->add($userId, $productId,1);
        }
        else {
            $this->userProductRepository->updateQuantityPlus($userId, $productId,1);
        }
    }

    public function removeProduct(int $productId): void
    {
        $user = $this->authenticationService->getCurrentUser();

        if (!$user instanceof User){
            return;
        }

        $userId = $user->getId();

        $userProduct = $this->userProductRepository->getOneByUserIdProductId($userId,$productId);

        if (!empty($userProduct)) {
            if ($userProduct->getQuantity() === 1) {
                $this->userProductRepository->remove($userId, $productId);
            } elseif ($userProduct->getQuantity() !== 0){
                $this->userProductRepository->updateQuantityMinus($userId, $productId,1);
            }
        }
    }

    public function getCount(): int
    {
        $count = 0;
        foreach ($this->getProducts() as $userProduct) {
            $count += $userProduct->getQuantity();
        }

        return $count;
    }

    /**
     * @return array<int, UserProduct>
     */
    public function getProducts(): array
    {
        $user = $this->authenticationService->getCurrentUser();

        if (!$user instanceof User){
            return [];
        }

        $userId = $user->getId();

        return $this->userProductRepository->getAllByUserId($userId);
    }

    public function clear(): void
    {
        $user = $this->authenticationService->getCurrentUser();

        if (!$user instanceof User){
            return;
        }

        $userId = $user->getId();

        $this->userProductRepository->clearByUserId($userId);
    }

    public function deleteProduct(int $productId): void
    {
        $user = $this->authenticationService->getCurrentUser();

        if (!$user instanceof User){
            return;
        }

        $userId = $user->getId();

        $this->userProductRepository->clearProductByUserIdProductId($userId,$productId);
    }
}