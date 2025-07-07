<?php

declare(strict_types=1);

namespace App\GraphQL\Input;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

final class OrderItemInputType
{
    public static function build(): InputObjectType
    {
        return new InputObjectType([
            'name' => 'OrderItemInput',
            'fields' => [
                'sku' => Type::nonNull(Type::string()),
                'qty' => Type::nonNull(Type::int()),
                'attributes' => Type::nonNull(Type::listOf(Type::nonNull(self::attributeInputType()))),
            ],
        ]);
    }

    private static function attributeInputType(): InputObjectType
    {
        return new InputObjectType([
            'name' => 'AttributeSelectionInput',
            'fields' => [
                'id' => Type::nonNull(Type::int()),
                'value' => Type::nonNull(Type::string()),
                'display_value' => Type::string(),
            ],
        ]);
    }
}
