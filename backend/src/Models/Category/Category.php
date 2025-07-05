<?php

declare(strict_types=1);

namespace App\Models\Category;

use App\Models\Product\ProductFactory;
use App\Database\Database;

/**
 * Concrete Category model.
 *
 * Loads category data and resolves products.
 */
class Category extends AbstractCategory
{
    public function __construct(int $id)
    {
        parent::__construct($id, Database::getConnection());
    }

    protected function load(): void
    {
        $qb = $this->connection->createQueryBuilder();
        $row = $qb->select('name')
            ->from('categories')
            ->where('id = :id')
            ->setParameter('id', $this->id)
            ->executeQuery()
            ->fetchAssociative();

        if (!$row) {
            throw new \InvalidArgumentException("Category with ID {$this->id} not found.");
        }

        $this->name = (string) $row['name'];
    }

    public function getProducts(): array
    {
        $qb = $this->connection->createQueryBuilder();
        $rows = $qb->select('id')
            ->from('products')
            ->where('category_id = :categoryId')
            ->setParameter('categoryId', $this->id)
            ->executeQuery()
            ->fetchAllAssociative();

        $products = [];
        foreach ($rows as $row) {
            $products[] = ProductFactory::create((int) $row['id']);
        }

        return $products;
    }
}
