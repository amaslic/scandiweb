<?php

namespace App\Controller;

use App\GraphQL\GraphQLSchema;
use GraphQL\GraphQL as GraphQLBase;
use RuntimeException;
use Throwable;

class GraphQL
{
    public static function handle()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("HTTP/1.1 200 OK");
            exit();
        }

        try {
            $schema = GraphQLSchema::create();

            $rawInput = file_get_contents('php://input');
            if ($rawInput === false) {
                throw new RuntimeException('Failed to get php://input');
            }

            $input = json_decode($rawInput, true);
            $query = $input['query'] ?? null;
            $variableValues = $input['variables'] ?? null;

            if (!$query) {
                throw new RuntimeException('No GraphQL query provided.');
            }

            $result = GraphQLBase::executeQuery($schema, $query, null, null, $variableValues);
            $output = $result->toArray();
        } catch (Throwable $e) {
            $output = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }

        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($output);
    }
}
