<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Resolvers\AttributeSetResolver;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Defines the GraphQL AttributeSet type.
 */
final class AttributeSetType
{
    /**
     * Builds and returns the AttributeSet ObjectType.
     *
     * @param ObjectType $attributeValueType The nested Attribute value type.
     * @param AttributeSetResolver $resolver The resolver to handle field logic.
     * @return ObjectType
     */
    public static function build(
        ObjectType $attributeValueType,
        AttributeSetResolver $resolver
    ): ObjectType {
        return new ObjectType([
            'name' => 'AttributeSet',
            'fields' => function () use ($attributeValueType, $resolver) {
                return [
                    'id' => [
                        'type' => Type::nonNull(Type::int()),
                        'resolve' => fn ($set) => $resolver->resolveId($set),
                    ],
                    'name' => [
                        'type' => Type::nonNull(Type::string()),
                        'resolve' => fn ($set) => $resolver->resolveName($set),
                    ],
                    'type' => [
                        'type' => Type::nonNull(Type::string()),
                        'resolve' => fn ($set) => $resolver->resolveType($set),
                    ],
                    'items' => [
                        'type' => Type::listOf($attributeValueType),
                        'resolve' => fn ($set) => $resolver->resolveItems($set),
                    ],
                ];
            },
        ]);
    }
}
