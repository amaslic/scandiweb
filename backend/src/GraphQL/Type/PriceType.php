<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Defines the GraphQL Price type with nested Currency.
 */
final class PriceType
{
    /**
     * Builds and returns the Price ObjectType.
     *
     * @param ObjectType $currencyType The Currency GraphQL type.
     * @return ObjectType
     */
    public static function build(ObjectType $currencyType): ObjectType
    {
        return new ObjectType([
            'name' => 'Price',
            'fields' => [
                'amount' => [
                    'type' => Type::nonNull(Type::float()),
                    'resolve' => fn ($price) => $price->getAmount(),
                ],
                'currency' => [
                    'type' => Type::nonNull($currencyType),
                    'resolve' => fn ($price) => $price->getCurrency(),
                ],
            ],
        ]);
    }
}
