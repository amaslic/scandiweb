<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\Database\Database;
use App\Repository\AttributeValueRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductGalleryRepository;
use App\GraphQL\Types\PriceType;

class ProductType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Product',
            'fields' => [
                // Use SKU as ID (to match JSON)
                'id' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => fn($product) => $product->getSku()
                ],
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => fn($product) => $product->getName()
                ],
                'price' => [
                    'type' => Type::nonNull(Type::float()),
                    'resolve' => fn($product) => $product->getPrice()
                ],
                'prices' => [
                    'type' => Type::listOf(PriceType::get()),
                    'resolve' => fn($product) => [
                        [
                            'amount' => $product->getPrice(),
                            'currency' => [
                                'label' => 'USD',
                                'symbol' => '$'
                            ]
                        ]
                    ]
                ],
                'category' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function ($product) {
                        $pdo = Database::getConnection();
                        $repo = new CategoryRepository($pdo);
                        $category = $repo->findById($product->getCategoryId());
                        return $category ? $category->getName() : null;
                    }
                ],
                'brand' => [
                    'type' => Type::string(),
                    'resolve' => fn($product) => $product->getBrand()
                ],
                'inStock' => [
                    'type' => Type::boolean(),
                    'resolve' => fn($product) => $product->isInStock()
                ],
                'description' => [
                    'type' => Type::string(),
                    'resolve' => fn($product) => $product->getDescription()
                ],
                'gallery' => [
                    'type' => Type::listOf(Type::nonNull(Type::string())),
                    'resolve' => function ($product) {
                        try {
                            $pdo = Database::getConnection();
                            $repo = new ProductGalleryRepository($pdo);
                            return array_map(
                                fn($img) => $img->getImageUrl(),
                                $repo->findByProductId($product->getId())
                            );
                        } catch (\Throwable $e) {
                            error_log('Gallery resolve error: ' . $e->getMessage());
                            return null;
                        }
                    }

                ],
                'attributes' => [
                    'type' => Type::listOf(AttributeSetType::get()),
                    'resolve' => function ($product) {
                        $pdo = Database::getConnection();
                        $repo = new AttributeValueRepository($pdo);
                        $values = $repo->findByProductId($product->getId());

                        $grouped = [];

                        foreach ($values as $value) {
                            $attr = $value->getAttribute();
                            $key = $attr->getId();
            
                            if (!isset($grouped[$key])) {
                                $grouped[$key] = [
                                    'id' => $attr->getName(),
                                    'name' => $attr->getName(),
                                    'type' => 'text',
                                    'items' => []
                                ];
                            }

                            $grouped[$key]['items'][] = [
                                'id' => $value->getValue(),
                                'value' => $value->getValue(),
                                'displayValue' => $value->getValue(),
                            ];
                        }

                        return array_values($grouped);
                    }
                ]

            ]
        ]);
    }

    public static function get(): ObjectType
    {
        return new self();
    }
}
