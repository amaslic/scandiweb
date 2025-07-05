<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Models\Currency\Currency;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class CurrencyType
{
    public static function build(): ObjectType
    {
        return new ObjectType([
            'name' => 'Currency',
            'fields' => [
                'label' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => fn(Currency $currency) => $currency->getLabel(),
                ],
                'symbol' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => fn(Currency $currency) => $currency->getSymbol(),
                ],
            ],
        ]);
    }
}
