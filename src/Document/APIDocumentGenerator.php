<?php

namespace AlirezaH\OpenApi\Document;

use AlirezaH\OpenApi\Document\OperationGenerator\AuthOpenAPIOperationGenerator;
use AlirezaH\OpenApi\Lib\OpenAPIDocumentGenerator;

class APIDocumentGenerator
{
    private const OPEN_API_CONFIG = [
        'openapi' => '3.0.0',
        'info' => [
            'title' => 'API',
            'description' => 'API',
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
                        'default' => 'api'
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
        'externalDocument' => []
    ];

    private const OPEN_API_OPERATION_GENERATORS = [
        AuthOpenAPIOperationGenerator::class,
    ];

    public function docs(): string
    {
        return (new OpenAPIDocumentGenerator(
            self::OPEN_API_CONFIG,
            self::OPEN_API_OPERATION_GENERATORS
        ))->docs();
    }
}