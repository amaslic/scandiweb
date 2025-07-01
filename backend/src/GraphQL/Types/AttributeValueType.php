<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AttributeValueType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'AttributeValue',
            'fields' => [
                'value' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => fn($attrValue) => $attrValue->getValue()
                ],
                'attribute' => [
                    'type' => AttributeType::get(),
                    'resolve' => fn($attrValue) => $attrValue->getAttribute()
                ],
            ],
        ]);
    }

    public static function get(): ObjectType
    {
        return new self();
    }
}
