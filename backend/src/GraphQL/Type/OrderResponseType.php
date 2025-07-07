<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class OrderResponseType
{
    public static function build(): ObjectType
    {
        return new ObjectType([
            'name' => 'OrderResponse',
            'fields' => [
                'success' => Type::nonNull(Type::boolean()),
                'message' => Type::nonNull(Type::string()),
                'orderId' => Type::int(),
            ],
        ]);
    }
}
