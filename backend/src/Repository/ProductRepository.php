<?php

namespace App\Repository;

use App\Model\Product;
use PDO;
use App\Repository\ProductGalleryRepository;

class ProductRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return Product[]
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $galleryRepo = new ProductGalleryRepository($this->pdo);

        return array_map(function ($row) use ($galleryRepo) {
            $product = new Product(
                (int) $row['id'],
                $row['sku'],
                $row['name'],
                (float) $row['price'],
                (int) $row['category_id'],
                $row['brand'],
                (bool) $row['in_stock'],
                $row['description'],
                [] // gallery will be set below
            );

            $gallery = $galleryRepo->findByProductId($product->getId());
            $product->setGallery(array_map(fn($img) => $img->getImageUrl(), $gallery));

            return $product;
        }, $rows);
    }

    /**
     * Get product by ID.
     *
     * @param int $id
     * @return Product|null
     */
    public function findById(int $id): ?Product
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $product = new Product(
            (int) $row['id'],
            $row['sku'],
            $row['name'],
            (float) $row['price'],
            (int) $row['category_id'],
            $row['brand'],
            (bool) $row['in_stock'],
            $row['description'],
            []
        );

        $galleryRepo = new ProductGalleryRepository($this->pdo);
        $gallery = $galleryRepo->findByProductId($product->getId());
        $product->setGallery(array_map(fn($img) => $img->getImageUrl(), $gallery));

        return $product;
    }

    /**
     * Get product by SKU.
     *
     * @param string $sku
     * @return Product|null
     */
    public function findBySku(string $sku): ?Product
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE sku = :sku");
        $stmt->execute(['sku' => $sku]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $product = new Product(
            (int) $row['id'],
            $row['sku'],
            $row['name'],
            (float) $row['price'],
            (int) $row['category_id'],
            $row['brand'],
            (bool) $row['in_stock'],
            $row['description'],
            []
        );

        $galleryRepo = new ProductGalleryRepository($this->pdo);
        $gallery = $galleryRepo->findByProductId($product->getId());
        $product->setGallery(array_map(fn($img) => $img->getImageUrl(), $gallery));

        return $product;
    }
}
