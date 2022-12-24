<?php

namespace AlirezaH\OpenApiGenerator\Lib;

use Faker\Factory;
use Faker\Generator;

class OpenAPIOperationGenerator
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
