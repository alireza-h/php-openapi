<?php

namespace AlirezaH\OpenApi\Lib;

class OpenAPIResponse
{
    private string $status = '200';
    private string $description = '';
    private array $headers = [];
    private array $examples = [];

    private function __construct()
    {
    }

    public static function create(): self
    {
        return new static();
    }

    public function status(string $status = '200'): self
    {
        $this->status = $status;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function header(
        string $name,
        string $type = 'string',
        string $example = '',
        string $description = ''
    ): self {
        $this->headers[$name] = [
            'description' => $description,
            'schema' => [
                'type' => $type,
                'example' => $example
            ]
        ];

        return $this;
    }

    public function example(
        array $example,
        string $mediaType = 'application/json',
        array $schema = []
    ): self {
        !empty($schema) && $this->examples[$mediaType]['schema'] = $schema;
        $this->examples[$mediaType]['example'] = $example;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function serialize(): array
    {
        return [
            'description' => $this->description,
            'headers' => $this->headers ?: (object)[],
            'content' => $this->examples ?: (object)[]
        ];
    }
}
