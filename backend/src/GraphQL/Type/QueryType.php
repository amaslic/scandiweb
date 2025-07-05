<?php
declare(strict_types=1);

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Resolvers\ProductResolver;
use App\Resolvers\CategoryResolver;

final class QueryType
{
    private const DEFAULT_CATEGORY_ID = 1;

    public static function build(
        ObjectType $categoryType,
        ObjectType $productType,
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        ProductResolver $productResolver,
        CategoryResolver $categoryResolver
    ): ObjectType {
        return new ObjectType([
            'name' => 'Query',
            'fields' => self::getFields(
                $categoryType,
                $productType,
                $productResolver,
                $categoryResolver
            ),
        ]);
    }

    private static function getFields(
        ObjectType $categoryType,
        ObjectType $productType,
        ProductResolver $productResolver,
        CategoryResolver $categoryResolver
    ): array {
        return [
            'categories' => [
                'type' => Type::listOf($categoryType),
                'resolve' => fn(): array => $categoryResolver->resolveAll(),
            ],
            'products' => [
                'type' => Type::listOf($productType),
                'args' => ['categoryId' => Type::int()],
                'resolve' => function ($root, array $args) use ($productResolver): array {
                    if (($args['categoryId'] ?? null) === self::DEFAULT_CATEGORY_ID) {
                        unset($args['categoryId']);
                    }
                    return $productResolver->resolveAll($args);
                },
            ],
            'product' => [
                'type' => $productType,
                'args' => ['sku' => Type::nonNull(Type::string())],
                'resolve' => fn($root, array $args) => $productResolver->resolveBySku($args),
            ],
        ];
    }
}
