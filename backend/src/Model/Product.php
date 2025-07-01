<?php

namespace App\Model;

class Product
{
    private int $id;
    private string $sku;
    private string $name;
    private float $price;
    private int $categoryId;
    private string $brand;
    private bool $inStock;
    private string $description;
    private array $gallery = [];

    public function __construct(int $id, string $sku, string $name, float $price, int $categoryId, string $brand, bool $inStock, string $description, array $gallery)
    {
        $this->id = $id;
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->categoryId = $categoryId;
        $this->brand = $brand;
        $this->inStock = $inStock;
        $this->description = $description;
        $this->gallery = $gallery;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getSku(): string
    {
        return $this->sku;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getPrice(): float
    {
        return $this->price;
    }
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }
    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function isInStock(): bool
    {
        return (bool) $this->inStock;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setGallery(array $gallery): void
    {
        $this->gallery = $gallery;
    }

    public function getGallery(): array
    {
        return $this->gallery;
    }
}
