<?php
declare(strict_types=1);

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\Models\Category\Category as CategoryModel;

/**
 * Encapsulates the GraphQL Category type definition.
 */
final class CategoryType
{
    /**
     * Builds the Category ObjectType.
     *
     * @param ObjectType &$categoryType  Placeholder for recursion
     * @param ObjectType &$productType   GraphQL Product type (by reference)
     * @return ObjectType
     */
    public static function build(
        &$categoryType,
        &$productType
    ): ObjectType {
        $categoryType = new ObjectType([
            'name' => 'Category',
            'fields' => function () use (&$categoryType, &$productType) {
                return [
                    'id' => [
                        'type' => Type::nonNull(Type::int()),
                        'resolve' => fn(CategoryModel $c) => $c->getId(),
                    ],
                    'name' => [
                        'type' => Type::nonNull(Type::string()),
                        'resolve' => fn(CategoryModel $c) => $c->getName(),
                    ],
                    'products' => [
                        'type' => Type::listOf($productType),
                        'resolve' => fn(CategoryModel $c) => $c->getProducts(),
                    ],
                ];
            },
        ]);

        return $categoryType;
    }
}