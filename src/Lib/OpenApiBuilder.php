<?php

namespace AlirezaH\OpenApi\Lib;

class OpenApiBuilder
{
    private array $docs = [];
    /**
     * @var OpenApiOperation[]
     */
    private array $operations = [];

    private function __construct()
    {
    }

    public static function openapi(string $openapi = '3.0.0'): self
    {
        $self = new static();

        $self->docs = [
            'openapi' => $openapi,
        ];

        return $self;
    }

    public function info(
        array $info = [
            'title' => 'API',
            'description' => '',
            'version' => '1.0.0',
        ]
    ): self {
        $this->docs['info'] = $info;

        return $this;
    }

    public function server(array $server): self
    {
        $this->docs['servers'][] = $server;

        return $this;
    }

    public function component(string $type, string $name, array $component): self
    {
        $this->docs['components'][$type][$name] = $component;

        return $this;
    }

    public function security(array $security): self
    {
        $this->docs['security'][] = $security;

        return $this;
    }

    public function tag(string $name, string $description = ''): self
    {
        $this->docs['tags'][] = [
            'name' => $name,
            'description' => $description
        ];

        return $this;
    }

    public function externalDocs(string $description, string $url): self
    {
        $this->docs['externalDocs'][] = [
            'description' => $description,
            'url' => $url
        ];

        return $this;
    }

    public function operation(OpenApiOperation $operation): self
    {
        $this->operations[] = $operation;

        return $this;
    }

    public function docs(): string
    {
        $this->docs['paths'] = [];
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
