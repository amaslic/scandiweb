<?php
declare(strict_types=1);

namespace App\Controller;

use App\GraphQL\SchemaBuilder;
use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Error\DebugFlag;
use RuntimeException;
use Throwable;

class GraphQL
{
    /**
     * Handles incoming GraphQL requests via FastRoute.
     *
     * @param array $vars Route variables (unused)
     * @return string JSON response
     */
    public static function handle(array $vars = []): string
    {
        // CORS headers
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header("HTTP/1.1 200 OK");
            return '';
        }

        try {
            $schema = SchemaBuilder::build();

            $rawInput = file_get_contents('php://input');
            if ($rawInput === false) {
                throw new RuntimeException('Failed to read php://input');
            }

            $input          = json_decode($rawInput, true);
            $query          = $input['query']     ?? null;
            $variableValues = $input['variables'] ?? null;

            if (!$query) {
                throw new RuntimeException('No GraphQL query provided.');
            }

            // 8-argument executeQuery without debug flag
            $result = GraphQLBase::executeQuery(
                $schema,
                $query,
                /* rootValue       */ null,
                /* contextValue    */ null,
                /* variableValues  */ $variableValues,
                /* operationName   */ null,
                /* fieldResolver   */ null,
                /* validationRules */ null
            );

            // Include debug messages in output
            $output = $result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE);

        } catch (Throwable $e) {
            $output = [
                'errors' => [
                    [
                        'message'    => $e->getMessage(),
                        'extensions' => ['category' => 'internal'],
                    ],
                ],
            ];
        }

        header('Content-Type: application/json; charset=UTF-8');
        return json_encode($output);
    }
}
