<?php

namespace App\GraphQL;

use App\GraphQL\Types\QueryType;
use App\GraphQL\Types\MutationType;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;

class GraphQLSchema
{
    public static function create(): Schema
    {
        return new Schema(
            (new SchemaConfig())
                ->setQuery(QueryType::get())
                ->setMutation(MutationType::get())
        );
    }
}
