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
use App\GraphQL\Type\OrderResponseType;
use App\GraphQL\Input\OrderItemInputType;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use App\Resolvers\AttributeSetResolver;
use App\Resolvers\CategoryResolver;
use App\Resolvers\ProductResolver;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use App\GraphQL\Mutation\PlaceOrderMutation;

final class SchemaBuilder
{
    public static function build(): Schema
    {
        // Initialize DB connection
        $connection = Database::getConnection();

        // Instantiate repositories
        $categoryRepository = new CategoryRepository($connection);
        $productRepository = new ProductRepository($connection);
        $orderRepository = new OrderRepository($connection);

        // Instantiate service
        $orderService = new OrderService($connection, $productRepository);

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

        // Root query
        $queryType = QueryType::build(
            $categoryType,
            $productType,
            $categoryRepository,
            $productRepository,
            $productResolver,
            $categoryResolver
        );

        // Mutation
        $mutationType = new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'placeOrder' => [
                    'type' => OrderResponseType::build(),
                    'args' => [
                        'items' => Type::nonNull(Type::listOf(OrderItemInputType::build())),
                    ],
                    'resolve' => fn($root, array $args) => $orderService->placeOrder($args['items']),
                ],
            ],
        ]);

        $mutationType = new ObjectType([
            'name' => 'Mutation',
            'fields' => array_merge(
                PlaceOrderMutation::getMutation($connection, $productRepository)
            ),
        ]);

        return new Schema([
            'query' => $queryType,
            'mutation' => $mutationType,
        ]);
    }
}
