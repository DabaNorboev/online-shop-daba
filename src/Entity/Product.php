<?php

namespace Entity;

class Product
{
    private int $id;
    private string $name;
    private string $description;
    private int $price;
    private string $imgUrl;

    public function __construct(int $id, string $name, string $description, int $price, string $imgUrl)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->imgUrl = $imgUrl;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
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
}