<?php

namespace App\GraphQL\Mutations;

use App\Database\Database;
use App\Repository\ProductRepository;
use App\GraphQL\Types\OrderType;
use GraphQL\Type\Definition\Type;

class OrderMutation
{
    public static function get(): array
    {
        return [
            'placeOrder' => [
                'type' => OrderType::get(),
                'args' => [
                    'skuList' => Type::nonNull(Type::listOf(Type::nonNull(Type::string())))
                ],
                'resolve' => function ($root, $args) {
                    $pdo = Database::getConnection();
                    $repo = new ProductRepository($pdo);

                    $total = 0;
                    $items = [];

                    $notFound = [];

                    foreach ($args['skuList'] as $sku) {
                        $product = $repo->findBySku($sku);
                        if ($product) {
                            $total += $product->getPrice();
                            $items[] = $sku;
                        } else {
                            $notFound[] = $sku;
                        }
                    }

                    return [
                        'success' => true,
                        'message' => 'Order received successfully.',
                        'total' => $total,
                        'items' => $items,
                        'notFound' => $notFound,
                    ];
                }
            ]
        ];
    }
}
