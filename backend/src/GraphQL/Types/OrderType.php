<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class OrderType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Order',
            'fields' => [
                'success' => Type::nonNull(Type::boolean()),
                'message' => Type::nonNull(Type::string()),
                'total' => Type::nonNull(Type::float()),
                'items' => Type::nonNull(Type::listOf(Type::string())),
                'notFound' => Type::listOf(Type::string()),
            ]
        ]);
    }

    public static function get(): ObjectType
    {
        return new self();
    }
}
