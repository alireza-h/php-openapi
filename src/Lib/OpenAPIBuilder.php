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

    public function endpoint(OpenAPIOperation $endpoint): self
    {
        $this->endpoints[] = $endpoint;

        return $this;
    }

    public function docs(): string
    {
        foreach ($this->endpoints as $endpoint) {
            $this->docs['paths'][$endpoint->getPath()][$endpoint->getMethod()] = $endpoint->serialize();
        }

        return json_encode($this->docs, JSON_PRETTY_PRINT);
    }

    public function save(string $path): void
    {
        file_put_contents($path, $this->docs());
    }
}
