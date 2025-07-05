<?php
declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Models\Attribute\AttributeItem;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class AttributeValueType
{
    public static function build(): ObjectType
    {
        return new ObjectType([
            'name'   => 'AttributeValue',
            'fields' => [
                'value' => [
                    'type'    => Type::nonNull(Type::string()),
                    'resolve' => fn(AttributeItem $item) => $item->getValue(),
                ],
                'displayValue' => [
                    'type'    => Type::string(),
                    'resolve' => fn(AttributeItem $item) => $item->getDisplayValue(),
                ],
            ],
        ]);
    }
}
