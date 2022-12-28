<?php

namespace AlirezaH\OpenApi\Lib;

class OpenAPIOperation
{
    private string $path;
    private string $method;
    private array $tags = [];
    private string $summary = '';
    private string $description = '';
    private array $headers = [];
    private array $params = [];
    private ?OpenAPIRequestBody $requestBody = null;
    private array $responses = [];

    private function __construct()
    {
    }

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

    /**
     * @param string|array $description use array and \n for multiline description
     * @return $this
     */
    public function description($description): self
    {
        $this->description = implode('', (array)$description);

        return $this;
    }

    /**
     * @param array $headers = [
     *  index => [
     *      'name' => 'name',
     *      'type' => 'string',
     *      'example' => '',
     *      'description' => '',
     *  ]
     * ]
     * @return $this
     */
    public function headers(array $headers): self
    {
        $this->headers = $headers;

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

    public function requestBody(OpenAPIRequestBody $requestBody): self
    {
        $this->requestBody = $requestBody;

        return $this;
    }

    public function response(OpenAPIResponse $response): self
    {
        $this->responses[] = $response;

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
                'requestBody' => $this->requestBody !== null ? $this->requestBody->serialize() : null,
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
                            'example' => '',
                        ];
                    }

                    foreach ($this->headers as $header) {
                        $parameters[] = [
                            'name' => $header['name'],
                            'in' => 'header',
                            'schema' => [
                                'type' => $header['type'] ?? 'string'
                            ],
                            'example' => $header['example'] ?? '',
                            'description' => $header['description'] ?? '',
                        ];
                    }

                    foreach ($this->params as $param) {
                        $parameters[] = [
                            'name' => $param['name'],
                            'in' => 'query',
                            'schema' => [
                                'type' => $param['type'] ?? 'string'
                            ],
                            'example' => $param['example'] ?? '',
                            'description' => $param['description'] ?? '',
                        ];
                    }

                    return $parameters;
                })(),
                'responses' => (function () {
                    $responses = [];
                    foreach ($this->responses as $response) {
                        $responses[$response->getStatus()] = $response->serialize();
                    }
                    
                    return $responses;
                })(),
            ]
        );
    }
}
