<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Models\Product\AbstractProduct;
use App\Resolvers\ProductResolver;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Encapsulates the GraphQL Product type definition.
 */
final class ProductType
{
    /**
     * Builds the Product ObjectType.
     *
     * @param ObjectType &$productType      Placeholder for recursion
     * @param ObjectType  $categoryType     GraphQL Category type
     * @param ObjectType  $priceType        GraphQL Price type
     * @param ObjectType  $attributeSetType GraphQL AttributeSet type
     * @param ProductResolver $productResolver Resolver for product fields
     * @return ObjectType
     */
    public static function build(
        &$productType,
        ObjectType $categoryType,
        ObjectType $priceType,
        ObjectType $attributeSetType,
        ProductResolver $productResolver
    ): ObjectType {
        $productType = new ObjectType([
            'name' => 'Product',
            'fields' => function () use (
                &$productType,
                $categoryType,
                $priceType,
                $attributeSetType,
                $productResolver
            ) {
                return [
                    'id' => [
                        'type' => Type::nonNull(Type::int()),
                        'resolve' => fn(AbstractProduct $p) => $p->getId(),
                    ],
                    'sku' => [
                        'type' => Type::nonNull(Type::string()),
                        'resolve' => fn(AbstractProduct $p) => $p->getSku(),
                    ],
                    'name' => [
                        'type' => Type::nonNull(Type::string()),
                        'resolve' => fn(AbstractProduct $p) => $p->getName(),
                    ],
                    'inStock' => [
                        'type' => Type::nonNull(Type::boolean()),
                        'resolve' => fn(AbstractProduct $p) => $p->isInStock(),
                    ],
                    'gallery' => [
                        'type' => Type::listOf(Type::string()),
                        'resolve' => fn(AbstractProduct $p) => $p->getGallery(),
                    ],
                    'description' => [
                        'type' => Type::string(),
                        'resolve' => fn(AbstractProduct $p) => $p->getDescription(),
                    ],
                    'brand' => [
                        'type' => Type::string(),
                        'resolve' => fn(AbstractProduct $p) => $p->getBrand(),
                    ],
                    'category' => [
                        'type' => $categoryType,
                        'resolve' => fn(AbstractProduct $p) => $productResolver->resolveCategory($p),
                    ],
                    'attributes' => [
                        'type' => Type::listOf($attributeSetType),
                        'resolve' => fn(AbstractProduct $p) => $productResolver->resolveAttributes($p),
                    ],
                    'prices' => [
                        'type' => Type::listOf($priceType),
                        'resolve' => fn(AbstractProduct $p) => $productResolver->resolvePrices($p),
                    ],
                ];
            },
        ]);

        return $productType;
    }
}
