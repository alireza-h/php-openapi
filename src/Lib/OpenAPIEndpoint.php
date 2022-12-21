<?php

namespace AlirezaH\OpenApiGenerator\Lib;

class OpenAPIEndpoint
{
    private array $data = [];

    public static function of(string $path): self
    {
        $self = new static();
        $self->data['path'] = $path;

        return $self;
    }

    public function serialize(): array
    {
        return array_filter($this->format());
    }

    public function method(string $method): self
    {
        $this->data['method'] = $method;

        return $this;
    }

    public function get(): self
    {
        return $this->method('get');
    }

    public function post(): self
    {
        return $this->method('post');
    }

    public function put(): self
    {
        return $this->method('put');
    }

    public function delete(): self
    {
        return $this->method('delete');
    }

    public function tags(array $tags): self
    {
        $this->data['tags'] = $tags;

        return $this;
    }

    public function summary(string $summary): self
    {
        $this->data['summary'] = $summary;

        return $this;
    }

    public function description($description): self
    {
        $this->data['description'] = implode('', (array)$description);

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
        $this->data['params'] = $params;

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
        $this->data['form_data'] = $formData;

        return $this;
    }

    public function response(array $example, int $status = 200): self
    {
        $this->data['responses'][$status] = $example;

        return $this;
    }

    private function format(): array
    {
        return array_filter(
            [
                'path' => $this->data['path'],
                'method' => $this->data['method'] ?? 'get',
                'tags' => $this->data['tags'] ?? [],
                'summary' => $this->data['summary'] ?? '',
                'description' => $this->data['description'] ?? '',
                'requestBody' => (function () {
                    if (empty($this->data['form_data'])) {
                        return null;
                    }

                    return [
                        'description' => '',
                        'required' => true,
                        'content' => [
                            in_array($this->data['method'], ['put', 'delete']) ?
                                'application/x-www-form-urlencoded' : 'multipart/form-data' => $requestBody = [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => (function () {
                                        $properties = [];
                                        foreach ($this->data['form_data'] as $property) {
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

                    preg_match_all('/({[a-z_?]+})/', $this->data['path'], $urlParams);
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

                    foreach ($this->data['params'] ?? [] as $param) {
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
                'responses' => (function () {
                    $responses = [];
                    foreach ($this->data['responses'] ?? [] as $status => $response) {
                        $responses[(string)$status] = [
                            'description' => (string)$status,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'example' => $response
                                    ]
                                ]
                            ],

                        ];
                    }

                    return $responses;
                })(),
            ]
        );
    }
}
