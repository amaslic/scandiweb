<?php

namespace App\Model;

class ProductGallery
{
    private int $id;
    private int $productId;
    private string $imageUrl;

    public function __construct(int $id, int $productId, string $imageUrl)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->imageUrl = $imageUrl;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getProductId(): int
    {
        return $this->productId;
    }
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }
}
