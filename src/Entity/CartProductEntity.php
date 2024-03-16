<?php

namespace Entity;

class CartProductEntity
{
    private string $id;
    private string $name;
    private float $price;
    private string $imgUrl;
    private int $quantity;
    private float $sum;
    public function __construct(string $id, string $name, string $price, string $imgUrl, string $quantity)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->imgUrl = $imgUrl;
        $this->quantity = $quantity;
        $this->sum = $price * $quantity;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    public function getImgUrl(): string
    {
        return $this->imgUrl;
    }

    public function setImgUrl(string $imgUrl): void
    {
        $this->imgUrl = $imgUrl;
    }

    public function getQuantity(): string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getSum(): string
    {
        return $this->sum;
    }

    public function setSum(string $sum): void
    {
        $this->sum = $sum;
    }

}