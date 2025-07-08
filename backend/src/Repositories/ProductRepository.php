<?php

declare(strict_types=1);

namespace App\Repositories;

use Doctrine\DBAL\Connection;

final class ProductRepository
{
    private Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        return $this->db->createQueryBuilder()
            ->select('id')
            ->from('products')
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function getByCategoryId(int $categoryId): array
    {
        return $this->db->createQueryBuilder()
            ->select('id')
            ->from('products')
            ->where('category_id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function getBySku(string $sku): ?array
    {
        $row = $this->db->createQueryBuilder()
            ->select('id')
            ->from('products')
            ->where('sku = :sku')
            ->setParameter('sku', $sku)
            ->executeQuery()
            ->fetchAssociative();

        return $row ?: null;
    }

    public function getDefaultPrice(int $productId): array
    {
        $row = $this->db->createQueryBuilder()
            ->select('*')
            ->from('prices')
            ->where('product_id = :product_id')
            ->setParameter('product_id', $productId)
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAssociative();

        if (!$row) {
            throw new \RuntimeException("No price found for product ID {$productId}");
        }

        return  $row;
    }

    public function getConnection(): Connection
    {
        return $this->db;
    }
}
