<?php

namespace App\Repositories;

use Doctrine\DBAL\Connection;

class OrderRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function createOrder(): int
    {
        $this->connection->insert('orders', []);
        return (int) $this->connection->lastInsertId();
    }

    public function addOrderItem(int $orderId, int $productId, int $quantity, int $priceId, float $unitPrice): int
    {
        $this->connection->insert('order_items', [
            'order_id' => $orderId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price_id' => $priceId,
            'unit_price' => $unitPrice,
        ]);
        return (int) $this->connection->lastInsertId();
    }

    public function addOrderItemAttribute(int $orderItemId, int $attributeId, string $value, string $displayValue): void
    {
        $this->connection->insert('order_item_attributes', [
            'order_item_id' => $orderItemId,
            'attribute_id' => $attributeId,
            'value' => $value,
            'display_value' => $displayValue,
        ]);
    }
}
