<?php

namespace AlirezaH\OpenApiGenerator\Lib;

use Faker\Factory;
use Faker\Generator;

class OpenAPIEndpointGenerator
{
    protected Generator $faker;

    public function __construct()
    {
        $this->faker = $this->makeFaker();
    }

    protected function makeFaker(): Generator
    {
        return Factory::create();
    }
}
