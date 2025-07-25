<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Doctrine\DBAL\Connection;

class OrderService
{
    private OrderRepository $orderRepository;
    private ProductRepository $productRepository;

    public function __construct(
        Connection $connection,
        ProductRepository $productRepository
    ) {
        $this->orderRepository = new OrderRepository($connection);
        $this->productRepository = $productRepository;
    }

    public function placeOrder(array $items): int
    {
        $orderId = $this->orderRepository->createOrder();

        foreach ($items as $item) {
            $sku = $item['sku'];
            $qty = $item['qty'];
            $attributes = $item['attributes'] ?? [];

            $product = $this->productRepository->getBySku($sku);

            if (!$product) {
                throw new \Exception("Product with SKU {$sku} not found.");
            }

            $productId = $product['id'];
            $priceRow = $this->productRepository->getDefaultPrice($productId);

            if (!$priceRow) {
                throw new \Exception("Default price not found for product ID {$productId}");
            }

            $priceId = $priceRow['id'];
            $unitPrice  = $priceRow['amount'];

            $orderItemId = $this->orderRepository->addOrderItem($orderId, $productId, $qty, $priceId, $unitPrice);

            foreach ($attributes as $attribute) {
                if (empty($attribute['id'])) {
                    // if attribute don't have an id, skip it
                    continue;
                }

                $attributeId = (int) $attribute['id'];
                $value = $attribute['value'];
                $displayValue = $attribute['display_value'] ?? $value;

                $this->orderRepository->addOrderItemAttribute($orderItemId, $attributeId, $value, $displayValue);
            }
        }

        return $orderId;
    }
}
