<?php
namespace App\GraphQL\Mutation;

use App\Services\OrderService;
use App\Repositories\ProductRepository;
use Doctrine\DBAL\Connection;
use GraphQL\Type\Definition\ResolveInfo;
use App\GraphQL\Type\OrderResponseType;
use App\GraphQL\Input\OrderItemInputType;

class PlaceOrderMutation
{
    public static function getMutation(Connection $connection, ProductRepository $productRepository): array
    {
        $orderService = new OrderService($connection, $productRepository);

        return [
            'placeOrder' => [
                'type' => OrderResponseType::build(),
                'args' => [
                    'items' => \GraphQL\Type\Definition\Type::nonNull(
                        \GraphQL\Type\Definition\Type::listOf(OrderItemInputType::build())
                    ),
                ],
                'resolve' => function ($root, array $args) use ($orderService) {
                    try {
                        $orderId = $orderService->placeOrder($args['items']);

                        // Fallback in case if order don't return an ID
                        if (!is_int($orderId)) {
                            throw new \Exception('Invalid order ID returned.');
                        }

                        return [
                            'success' => true,
                            'message' => 'Order placed successfully.',
                            'orderId' => $orderId,
                        ];
                    } catch (\Throwable $e) {
                        return [
                            'success' => false,
                            'message' => 'Error: ' . $e->getMessage(),
                            'orderId' => -1,
                        ];
                    }
                },

            ],
        ];
    }
}
