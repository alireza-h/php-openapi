<?php

namespace AlirezaH\OpenApi\Lib;

use Faker\Factory;
use Faker\Generator;

class OpenApiOperationGenerator
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
