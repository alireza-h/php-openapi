<?php

namespace AlirezaH\OpenApiGenerator\Lib;

class OpenAPIOperation
{
    private string $path;
    private string $method;
    private array $tags = [];
    private string $summary = '';
    private string $description = '';
    private array $params = [];
    private array $formData = [];
    private array $responses = [];

    public static function request(string $method, string $path): self
    {
        $self = new static();

        $self->method = $method;
        $self->path = $path;

        return $self;
    }

    public static function head(string $path): self
    {
        return static::request('head', $path);
    }

    public static function get(string $path): self
    {
        return static::request('get', $path);
    }

    public static function post(string $path): self
    {
        return static::request('post', $path);
    }

    public static function put(string $path): self
    {
        return static::request('put', $path);
    }

    public static function patch(string $path): self
    {
        return static::request('patch', $path);
    }

    public static function delete(string $path): self
    {
        return static::request('delete', $path);
    }

    public static function purge(string $path): self
    {
        return static::request('purge', $path);
    }

    public static function options(string $path): self
    {
        return static::request('options', $path);
    }

    public static function trace(string $path): self
    {
        return static::request('trace', $path);
    }

    public static function connect(string $path): self
    {
        return static::request('connect', $path);
    }

    public function tags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function summary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function description($description): self
    {
        $this->description = implode('', (array)$description);

        return $this;
    }

    /**
     * @param array $params = [
     *  index => [
     *      'name' => 'name',
     *      'type' => 'string',
     *      'example' => '',
     *      'description' => '',
     *  ]
     * ]
     * @return $this
     */
    public function params(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @param array $formData = [
     *  index => [
     *      'name' => 'name',
     *      'type' => 'string',
     *      'example' => '',
     *      'description' => '',
     *  ]
     * ]
     * @return $this
     */
    public function formData(array $formData): self
    {
        $this->formData = $formData;

        return $this;
    }

    public function response(
        array $example,
        int $status = 200,
        string $contentType = 'application/json',
        string $description = ''
    ): self {
        $this->responses[(string)$status]['description'] = $description;
        $this->responses[(string)$status]['content'][$contentType]['schema']['example'] = $example;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function serialize(): array
    {
        return array_filter($this->format());
    }

    private function format(): array
    {
        return array_filter(
            [
                'tags' => $this->tags,
                'summary' => $this->summary,
                'description' => $this->description,
                'requestBody' => (function () {
                    if (empty($this->formData)) {
                        return null;
                    }

                    return [
                        'description' => '',
                        'required' => true,
                        'content' => [
                            in_array($this->method, ['put', 'delete']) ?
                                'application/x-www-form-urlencoded' : 'multipart/form-data' => $requestBody = [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => (function () {
                                        $properties = [];
                                        foreach ($this->formData as $property) {
                                            $properties[$property['name']] = [
                                                'type' => $property['type'] ?? 'string',
                                                'example' => $property['example'],
                                                'description' => $property['description'] ?? '',
                                                'format' => $property['format'] ?? '',
                                            ];
                                        }

                                        return $properties;
                                    })()
                                ]
                            ],
                            'application/json' => $requestBody
                        ]
                    ];
                })(),
                'parameters' => (function () {
                    $parameters = [];

                    preg_match_all('/({[a-z_?]+})/', $this->path, $urlParams);
                    foreach ($urlParams[0] ?: [] as $urlParam) {
                        $parameters[] = [
                            'name' => str_replace(['{', '}'], '', $urlParam),
                            'in' => 'path',
                            'schema' => [
                                'type' => 'string'
                            ],
                            'required' => true,
                            'example' => ''
                        ];
                    }

                    foreach ($this->params as $param) {
                        $parameters[] = [
                            'name' => $param['name'],
                            'in' => 'query',
                            'schema' => [
                                'type' => 'string'
                            ],
                            'example' => $param['example']
                        ];
                    }

                    return $parameters;
                })(),
                'responses' => $this->responses,
            ]
        );
    }
}
