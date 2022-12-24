<?php

namespace AlirezaH\OpenApi\Lib;

use ReflectionClass;
use ReflectionMethod;

class OpenAPIDocumentGenerator
{
    private array $config;
    private array $openApiOperationGenerators;

    public function __construct(
        array $config = [
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'API',
                'description' => '',
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
            'externalDocs' => []
        ],
        array $openApiOperationGenerators = []
    ) {
        $this->config = $config;

        $this->openApiOperationGenerators = $openApiOperationGenerators;
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function docs(): string
    {
        $openApiBuilder = OpenAPIBuilder::openapi($this->config['openapi'])
            ->info($this->config['info']);

        foreach ($this->config['servers'] ?? [] as $server) {
            $openApiBuilder->server($server);
        }

        foreach ($this->config['components'] ?? [] as $componentType => $component) {
            foreach ($component as $name => $value) {
                $openApiBuilder->component($componentType, $name, $value);
            }
        }

        foreach ($this->config['security'] ?? [] as $security) {
            $openApiBuilder->security($security);
        }

        foreach ($this->config['tags'] ?? [] as $tag) {
            $openApiBuilder->tag($tag['name'], $tag['description']);
        }

        foreach ($this->config['externalDocs'] ?? [] as $externalDoc) {
            $openApiBuilder->externalDocs($externalDoc['description'], $externalDoc['url']);
        }

        foreach ($this->openApiOperationGenerators as $openApiOperationGeneratorClass) {
            $openApiOperationGenerator = new $openApiOperationGeneratorClass();
            $reflection = new ReflectionClass($openApiOperationGenerator);
            foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $openApiOperation) {
                if ($openApiOperation->isConstructor() || $openApiOperation->isDestructor()) {
                    continue;
                }
                $openApiBuilder->operation($openApiOperationGenerator->{$openApiOperation->name}());
            }
        }

        return $openApiBuilder->docs();
    }
}
