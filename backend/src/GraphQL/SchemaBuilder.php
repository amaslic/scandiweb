<?php

declare(strict_types=1);

namespace App\GraphQL;

use App\Database\Database;
use App\GraphQL\Type\AttributeSetType;
use App\GraphQL\Type\AttributeValueType;
use App\GraphQL\Type\CategoryType;
use App\GraphQL\Type\CurrencyType;
use App\GraphQL\Type\PriceType;
use App\GraphQL\Type\ProductType;
use App\GraphQL\Type\QueryType;
use App\Models\Product\ProductFactory;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Resolvers\AttributeSetResolver;
use App\Resolvers\CategoryResolver;
use App\Resolvers\ProductResolver;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;

final class SchemaBuilder
{
    public static function build(): Schema
    {
        // Initialize DB connection
        $connection = Database::getConnection();

        // Instantiate repositories
        $categoryRepository = new CategoryRepository($connection);
        $productRepository = new ProductRepository($connection);

        // Instantiate resolvers
        $productResolver = new ProductResolver($productRepository);
        $categoryResolver = new CategoryResolver($categoryRepository);
        $attributeSetResolver = new AttributeSetResolver(); 

        // Prepare types
        $categoryType = null;
        $productType = null;

        $currencyType = CurrencyType::build();
        $priceType = PriceType::build($currencyType);
        $attributeValueType = AttributeValueType::build();
        $attributeSetType = AttributeSetType::build($attributeValueType, $attributeSetResolver); 

        $categoryType = CategoryType::build($categoryType, $productType);
        $productType = ProductType::build($productType, $categoryType, $priceType, $attributeSetType, $productResolver);

        // Build root query with resolver injection
        $queryType = QueryType::build(
            $categoryType,
            $productType,
            $categoryRepository,
            $productRepository,
            $productResolver,
            $categoryResolver
        );

        // Mutation type for demo (fake order)
        $orderResponseType = new ObjectType([
            'name' => 'OrderResponse',
            'fields' => [
                'success' => Type::nonNull(Type::boolean()),
                'message' => Type::nonNull(Type::string()),
            ],
        ]);

        $mutationType = new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'createOrder' => [
                    'type' => $orderResponseType,
                    'args' => [
                        'skus' => Type::nonNull(Type::listOf(Type::nonNull(Type::int()))),
                    ],
                    'resolve' => function ($root, array $args) {
                        $total = 0;
                        foreach ($args['skus'] as $id) {
                            $prod = ProductFactory::create($id);
                            $prices = $prod->getPrices();
                            if (!empty($prices)) {
                                $total += $prices[0]->getAmount();
                            }
                        }

                        return [
                            'success' => true,
                            'message' => "Order received. Total calculated: $total",
                        ];
                    },
                ],
            ],
        ]);

        return new Schema([
            'query' => $queryType,
            'mutation' => $mutationType,
        ]);
    }
}
