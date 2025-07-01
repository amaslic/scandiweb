<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AttributeSetType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'AttributeSet',
            'fields' => [
                'id' => Type::nonNull(Type::string()),
                'name' => Type::nonNull(Type::string()),
                'type' => Type::nonNull(Type::string()),
                '__typename' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => fn() => 'AttributeSet'
                ],
                'items' => [
                    'type' => Type::listOf(Type::nonNull(
                        new ObjectType([
                            'name' => 'Attribute',
                            'fields' => [
                                'id' => Type::nonNull(Type::string()),
                                'value' => Type::nonNull(Type::string()),
                                'displayValue' => Type::nonNull(Type::string()),
                                '__typename' => [
                                    'type' => Type::nonNull(Type::string()),
                                    'resolve' => fn() => 'Attribute'
                                ]
                            ]
                        ])
                    ))
                ]
            ]
        ]);
    }

    public static function get(): ObjectType
    {
        return new self();
    }
}
