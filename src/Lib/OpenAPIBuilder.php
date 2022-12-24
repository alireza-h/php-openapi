<?php

namespace AlirezaH\OpenApi\Lib;

class OpenAPIBuilder
{
    private array $docs = [];
    private array $operations = [];

    private function __construct()
    {
    }

    public static function openapi(string $openapi = '3.0.0'): OpenAPIBuilder
    {
        $self = new static();

        $self->docs = [
            'openapi' => $openapi,
            'paths' => []
        ];

        return $self;
    }

    public function info(
        array $info = [
            'title' => 'API',
            'description' => '',
            'version' => '1.0.0',
        ]
    ): OpenAPIBuilder {
        $this->docs['info'] = $info;

        return $this;
    }

    public function server(array $server): OpenAPIBuilder
    {
        $this->docs['servers'][] = $server;

        return $this;
    }

    public function component(string $type, string $name, array $component): OpenAPIBuilder
    {
        $this->docs['components'][$type][$name] = $component;

        return $this;
    }

    public function security(array $security): OpenAPIBuilder
    {
        $this->docs['security'][] = $security;

        return $this;
    }

    public function tag(string $name, string $description = ''): OpenAPIBuilder
    {
        $this->docs['tags'][] = [
            'name' => $name,
            'description' => $description
        ];

        return $this;
    }

    public function externalDocs(string $description, string $url): OpenAPIBuilder
    {
        $this->docs['externalDocs'][] = [
            'description' => $description,
            'url' => $url
        ];

        return $this;
    }

    public function operation(OpenAPIOperation $operation): self
    {
        $this->operations[] = $operation;

        return $this;
    }

    public function docs(): string
    {
        foreach ($this->operations as $operation) {
            $this->docs['paths'][$operation->getPath()][$operation->getMethod()] = $operation->serialize();
        }

        return json_encode($this->docs, JSON_PRETTY_PRINT);
    }

    public function save(string $path): void
    {
        file_put_contents($path, $this->docs());
    }
}
