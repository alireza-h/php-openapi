<?php

namespace AlirezaH\OpenApi\Lib;

class OpenApiRequestBody
{
    private string $description = '';
    private bool $required = false;
    private array $mediaTypes = [];
    private array $properties = [];

    private function __construct()
    {
    }

    public static function create(): self
    {
        return new static();
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function required(bool $required = true): self
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @param array $property = [
     *  'name' => 'name',
     *  'type' => 'string',
     *  'format' => '',
     *  'example' => '',
     *  'description' => '',
     * ]
     * @return $this
     */
    public function property(array $property): self
    {
        $this->properties[] = $property;

        return $this;
    }

    /**
     * @param array $properties = [
     *  index => [
     *      'name' => 'name',
     *      'type' => 'string',
     *      'format' => '',
     *      'example' => '',
     *      'description' => '',
     *  ]
     * ]
     * @return $this
     */
    public function properties(array $properties): self
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @param string $mediaType
     * @param string $type
     * @return $this
     */
    public function mediaType(string $mediaType, string $type = 'object'): self
    {
        $this->mediaTypes[$mediaType] = $type;

        return $this;
    }

    public function mediaTypeMultipartFormData(string $type = 'object'): self
    {
        return $this->mediaType('multipart/form-data', $type);
    }

    public function mediaTypeXWwwFormUrlencoded(string $type = 'object'): self
    {
        return $this->mediaType('application/x-www-form-urlencoded', $type);
    }

    public function mediaTypeJson(string $type = 'object'): self
    {
        return $this->mediaType('application/json', $type);
    }

    public function serialize(): array
    {
        $properties = [];
        foreach ($this->properties as $property) {
            $properties[$property['name']] = [
                'type' => $property['type'] ?? 'string',
                'format' => $property['format'] ?? '',
                'example' => $property['example'] ?? '',
                'description' => $property['description'] ?? '',
            ];
        }

        $contents = [];
        foreach ($this->mediaTypes ?: ['multipart/form-data' => 'object'] as $mediaType => $type) {
            $contents[$mediaType] = [
                'schema' => [
                    'type' => $type,
                    'properties' => $properties
                ]
            ];
        }

        return [
            'description' => $this->description,
            'required' => $this->required,
            'content' => $contents
        ];
    }
}
