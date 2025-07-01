<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class CategoryType extends ObjectType
{
    private static ?self $instance = null;

    public function __construct()
    {
        parent::__construct([
            'name' => 'Category',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::int()),
                    'resolve' => fn($category) => $category->getId()
                ],
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => fn($category) => $category->getName()
                ]
            ]
        ]);
    }

    public static function get(): ObjectType
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
