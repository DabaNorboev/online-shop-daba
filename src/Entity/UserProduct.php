<?php

namespace Entity;

class UserProduct
{
    private string $id;
    private User $user;
    private Product $product;
    private int $quantity;
    public function __construct(string $id, User $user, Product $product, string $quantity)
    {
        $this->id = $id;
        $this->user = $user;
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

}