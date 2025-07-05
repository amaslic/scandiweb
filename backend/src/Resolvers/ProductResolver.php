<?php

declare(strict_types=1);

namespace App\Resolvers;

use App\Models\Category\Category;
use App\Models\Product\ProductFactory;
use App\Repositories\ProductRepository;
use GraphQL\Error\UserError;

final class ProductResolver
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function resolveBySku(array $args)
    {
        $row = $this->productRepository->getBySku($args['sku']);

        if (!$row) {
            throw new UserError("Product with SKU '{$args['sku']}' not found.");
        }

        return ProductFactory::create((int) $row['id'], $this->productRepository->getConnection());
    }

    public function resolveAll(array $args): array
    {
        $categoryId = $args['categoryId'] ?? null;
        $rows = $categoryId
            ? $this->productRepository->getByCategoryId($categoryId)
            : $this->productRepository->getAll();

        return array_map(
            fn (array $r) => ProductFactory::create((int) $r['id'], $this->productRepository->getConnection()),
            $rows
        );
    }

    public function resolveCategory($product): Category
    {
        return new Category($product->getCategoryId());
    }

    public function resolveAttributes($product): array
    {
        return $product->getAttributes();
    }

    public function resolvePrices($product): array
    {
        return $product->getPrices();
    }
}
