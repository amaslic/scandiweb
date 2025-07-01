<?php

namespace App\Repository;

use App\Model\ProductGallery;
use PDO;

class ProductGalleryRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return ProductGallery[]
     */
    public function findByProductId(int $productId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM product_gallery WHERE product_id = :product_id");
        $stmt->execute(['product_id' => $productId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(
            fn($row) =>
            new ProductGallery((int) $row['id'], (int) $row['product_id'], $row['image_url']),
            $rows
        );
    }
}
