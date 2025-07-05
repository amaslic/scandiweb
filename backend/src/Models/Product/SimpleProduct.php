<?php

declare(strict_types=1);

namespace App\Models\Product;

use App\Models\Attribute\AttributeSetFactory;
use App\Models\Price\Price;
use App\Models\Price\PriceCollection;
use InvalidArgumentException;

/**
 * SimpleProduct handles basic products with no variations.
 */
class SimpleProduct extends AbstractProduct
{
    protected function load(): void
    {
        $qb = $this->connection->createQueryBuilder();
        $row = $qb->select('id', 'sku', 'name', 'category_id', 'in_stock', 'description', 'brand')
            ->from('products')
            ->where('id = :id')
            ->setParameter('id', $this->id)
            ->executeQuery()
            ->fetchAssociative();

        if (!$row) {
            throw new InvalidArgumentException("Product with ID {$this->id} not found.");
        }

        $this->name = (string) $row['name'];
        $this->sku = (string) $row['sku'];
        $this->inStock = (bool) $row['in_stock'];
        $this->description = (string) $row['description'];
        $this->categoryId = (int) $row['category_id'];
        $this->brand = (string) $row['brand'];

        // Load gallery images
        $galleryRows = $this->connection->createQueryBuilder()
            ->select('image_url')
            ->from('product_gallery')
            ->where('product_id = :id')
            ->setParameter('id', $this->id)
            ->executeQuery()
            ->fetchAllAssociative();

        $this->gallery = array_map(fn($g) => (string) $g['image_url'], $galleryRows);
    }

    /**
     * @return Price[]
     */
    public function getPrices(): array
    {
        $collection = new PriceCollection($this->connection, $this->id);
        return $collection->getPrices();
    }

    /**
     * @return \App\Models\Attribute\AbstractAttributeSet[]
     */
    public function getAttributes(): array
    {
        $rows = $this->connection->createQueryBuilder()
            ->select('DISTINCT attribute_id')
            ->from('attribute_values')
            ->where('product_id = :id')
            ->setParameter('id', $this->id)
            ->executeQuery()
            ->fetchAllAssociative();

        $attributes = [];
        foreach ($rows as $row) {
            $attributes[] = AttributeSetFactory::create(
                $this->id,
                (int) $row['attribute_id']
            );
        }

        return $attributes;
    }
}
