<?php

namespace App\GraphQL\Types;

use App\GraphQL\Types\CategoryType;
use App\Repository\CategoryRepository;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\Database\Database;
use App\Repository\AttributeRepository;
use App\GraphQL\Types\AttributeType;
use App\GraphQL\Types\ProductType;
use App\Repository\ProductRepository;

class QueryType
{
    private static ?ObjectType $query = null;

    public static function get(): ObjectType
    {
        if (self::$query) {
            return self::$query;
        }

        self::$query = new ObjectType([
            'name' => 'Query',
            'fields' => [
                'categories' => [
                    'type' => Type::listOf(CategoryType::get()),
                    'resolve' => function () {
                        $pdo = Database::getConnection();
                        $repo = new CategoryRepository($pdo);
                        return $repo->getAll();
                    }
                ],
                'products' => [
                    'type' => Type::listOf(ProductType::get()),
                    'args' => [
                        'categoryId' => ['type' => Type::int()],
                    ],
                    'resolve' => function ($rootValue, array $args) {
                        $pdo = Database::getConnection();
                        $productRepo = new ProductRepository($pdo);

                        if (isset($args['categoryId'])) {
                            $categoryRepo = new CategoryRepository($pdo);
                            $allCategory = $categoryRepo->findByName('All');

                            // Ako je poslan categoryId i on NIJE "All", filtriraj
                            if (!$allCategory || (int) $args['categoryId'] !== $allCategory->getId()) {
                                return $productRepo->findByCategoryId((int) $args['categoryId']);
                            }
                        }

                        // Ako nije poslan ili jeste ali je "All" → vrati sve
                        return $productRepo->getAll();
                    }
                ],
                'product' => [
                    'type' => ProductType::get(),
                    'args' => [
                        'sku' => ['type' => Type::nonNull(Type::string())],
                    ],
                    'resolve' => function ($rootValue, $args) {
                        $pdo = Database::getConnection();
                        $repo = new ProductRepository($pdo);
                        return $repo->findBySku($args['sku']);
                    }
                ],
                'attributes' => [
                    'type' => Type::listOf(AttributeType::get()),
                    'resolve' => function () {
                        $pdo = Database::getConnection();
                        $repo = new AttributeRepository($pdo);
                        return $repo->getAll();
                    }
                ],
                'echo' => [
                    'type' => Type::string(),
                    'args' => [
                        'message' => ['type' => Type::string()],
                    ],
                    'resolve' => static fn($rootValue, array $args): string =>
                        'You said: ' . $args['message'],
                ],
            ],
        ]);

        return self::$query;
    }
}
