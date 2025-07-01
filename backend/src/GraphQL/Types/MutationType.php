<?php

namespace App\GraphQL\Types;

use App\GraphQL\Mutations\OrderMutation;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class MutationType
{
    private static ?ObjectType $mutation = null;

    public static function get(): ObjectType
    {
        if (self::$mutation) {
            return self::$mutation;
        }

        self::$mutation = new ObjectType([
            'name' => 'Mutation',
            'fields' => array_merge([
                'sum' => [
                    'type' => Type::int(),
                    'args' => [
                        'x' => ['type' => Type::int()],
                        'y' => ['type' => Type::int()],
                    ],
                    'resolve' => static fn($root, array $args): int =>
                        $args['x'] + $args['y'],
                ],
            ], OrderMutation::get())
        ]);

        return self::$mutation;
    }
}
