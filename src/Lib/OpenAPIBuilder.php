<?php

namespace AlirezaH\OpenApiGenerator\Lib;

class OpenAPIBuilder
{
    private array $docs = [];
    private array $endpoints = [];

    public static function of(array $definition = []): OpenAPIBuilder
    {
        $self = new static();

        $self->docs = array_merge(
            [
                'openapi' => '3.0.0',
                'info' => [
                    'title' => 'API',
                    'description' => '',
                    'version' => '1.0.0',
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
            ],
            $definition,
            ['paths' => []]
        );

        return $self;
    }

    public function endpoint(OpenAPIEndpoint $endpoint): self
    {
        $this->endpoints[] = $endpoint;

        return $this;
    }

    public function docs(): string
    {
        foreach ($this->endpoints as $endpoint) {
            $endpointData = $endpoint->serialize();
            $path = $endpointData['path'];
            $method = $endpointData['method'];
            unset($endpointData['path'], $endpointData['method']);
            $this->docs['paths'][$path][$method] = $endpointData;
        }

        return json_encode($this->docs, JSON_PRETTY_PRINT);
    }

    public function save(string $path): void
    {
        file_put_contents($path, $this->docs());
    }
}
