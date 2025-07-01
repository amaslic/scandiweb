<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AttributeType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Attribute',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::int()),
                    'resolve' => fn($attr) => $attr->getId()
                ],
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => fn($attr) => $attr->getName()
                ]
            ]
        ]);
    }

    public static function get(): ObjectType
    {
        return new self();
    }
}

