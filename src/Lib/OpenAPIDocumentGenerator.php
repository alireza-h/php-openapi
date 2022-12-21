<?php

namespace AlirezaH\OpenApiGenerator\Lib;

use ReflectionClass;
use ReflectionMethod;

class OpenAPIDocumentGenerator
{
    private array $config;
    private array $openApiEndpointGenerators;

    public function __construct(array $config = [], array $openApiEndpointGenerators = [])
    {
        $this->config = array_merge(
            [
                'info' => [
                    'title' => 'API',
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
            ],
            $config
        );

        $this->openApiEndpointGenerators = $openApiEndpointGenerators;
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function docs(): string
    {
        $openApiBuilder = OpenAPIBuilder::of([
            'openapi' => '3.0.0',
            'info' => $this->config['info'],
            'servers' => $this->config['servers']
        ]);

        foreach ($this->openApiEndpointGenerators as $openApiEndpointGeneratorClass) {
            $openApiEndpointGenerator = new $openApiEndpointGeneratorClass();
            $reflection = new ReflectionClass($openApiEndpointGenerator);
            foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $openApiEndpoint) {
                if ($openApiEndpoint->isConstructor() || $openApiEndpoint->isDestructor()) {
                    continue;
                }
                $openApiBuilder->endpoint($openApiEndpointGenerator->{$openApiEndpoint->name}());
            }
        }

        return $openApiBuilder->docs();
    }
}
