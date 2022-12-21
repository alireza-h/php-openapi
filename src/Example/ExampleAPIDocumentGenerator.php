<?php

namespace AlirezaH\OpenApiGenerator\Example;

use AlirezaH\OpenApiGenerator\Example\EndpointGenerator\ExampleApiAuthOpenAPIEndpointGenerator;
use AlirezaH\OpenApiGenerator\Lib\OpenAPIDocumentGenerator;

class ExampleAPIDocumentGenerator
{
    private const OPEN_API_CONFIG = [
        'info' => [
            'title' => 'Example API',
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
        ]
    ];

    private const OPEN_API_ENDPOINT_GENERATORS = [
        ExampleApiAuthOpenAPIEndpointGenerator::class,
    ];

    public function docs(): string
    {
        return (new OpenAPIDocumentGenerator(
            self::OPEN_API_CONFIG,
            self::OPEN_API_ENDPOINT_GENERATORS
        ))->docs();
    }
}