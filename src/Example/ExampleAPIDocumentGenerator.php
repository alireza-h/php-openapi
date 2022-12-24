<?php

namespace AlirezaH\OpenApi\Example;

use AlirezaH\OpenApi\Example\OperationGenerator\ExampleApiAuthOpenAPIOperationGenerator;
use AlirezaH\OpenApi\Lib\OpenAPIDocumentGenerator;

class ExampleAPIDocumentGenerator
{
    private const OPEN_API_CONFIG = [
        'openapi' => '3.0.0',
        'info' => [
            'title' => 'Example API',
            'description' => 'Example API',
            'version' => '1.0.0'
        ],
        'servers' => [
            [
                'url' => '{scheme}://{host}/{base_path}',
                'variables' => [
                    'scheme' => [
                        'enum' => [
                            'http',
                            'https'
                        ],
                        'default' => 'http'
                    ],
                    'host' => [
                        'default' => 'localhost:8000'
                    ],
                    'base_path' => [
                        'default' => 'ats-api'
                    ],
                ]
            ]
        ],
        'components' => [
            'securitySchemes' => [
                'bearerAuth' => [
                    'type' => 'http',
                    'scheme' => 'bearer',
                    'bearerFormat' => 'JWT',
                ]
            ]
        ],
        'security' => [
            [
                'bearerAuth' => []
            ]
        ],
        'tags' => [],
        'externalDocs' => []
    ];

    private const OPEN_API_OPERATION_GENERATORS = [
        ExampleApiAuthOpenAPIOperationGenerator::class,
    ];

    public function docs(): string
    {
        return (new OpenAPIDocumentGenerator(
            self::OPEN_API_CONFIG,
            self::OPEN_API_OPERATION_GENERATORS
        ))->docs();
    }
}