<?php

namespace AlirezaH\OpenApiGenerator\Example\OperationGenerator;


use AlirezaH\OpenApiGenerator\Lib\OpenAPIOperation;
use AlirezaH\OpenApiGenerator\Lib\OpenAPIOperationGenerator;

class ExampleApiAuthOpenAPIOperationGenerator extends OpenAPIOperationGenerator
{
    public function signup(): OpenAPIOperation
    {
        return OpenAPIOperation::post('/auth/signup')
            ->tags(['Auth'])
            ->summary('Signup')
            ->description('Signup description')
            ->formData(
                [
                    [
                        'name' => 'email',
                        'example' => $this->faker->email,
                        'description' => 'Email',
                    ],
                    [
                        'name' => 'password',
                        'type' => 'string',
                        'format' => 'password',
                        'example' => $password = $this->faker->password,
                        'description' => 'Password',
                    ],
                    [
                        'name' => 'password_confirmation',
                        'type' => 'string',
                        'format' => 'password',
                        'example' => $password,
                        'description' => 'Password',
                    ],
                ]
            )
            ->response(
                [
                    'data' => [],
                    'message' => null
                ]
            );
    }

    public function confirm(): OpenAPIOperation
    {
        return OpenAPIOperation::put('/auth/confirm')
            ->tags(['Auth'])
            ->summary('ConfirmSignup')
            ->description('Confirm signup description')
            ->formData(
                [
                    [
                        'name' => 'email',
                        'example' => $this->faker->email,
                        'description' => 'Email',
                    ],
                    [
                        'name' => 'code',
                        'example' => $this->faker->randomNumber(),
                    ]
                ]
            )
            ->response(
                [
                    'data' => [],
                    'message' => null
                ]
            );
    }

    public function signin(): OpenAPIOperation
    {
        return OpenAPIOperation::post('/auth/signin')
            ->tags(['Auth'])
            ->summary('Signin')
            ->description('Signin description')
            ->formData(
                [
                    [
                        'name' => 'email',
                        'example' => $this->faker->email,
                        'description' => 'Email',
                    ],
                    [
                        'name' => 'password',
                        'type' => 'string',
                        'format' => 'password',
                        'example' => $this->faker->password,
                    ]
                ]
            )
            ->response(
                [
                    'accessToken' => '_token',
                    'tokenType' => 'bearer',
                    'expiresIn' => 3600
                ]
            );
    }

    public function signout(): OpenAPIOperation
    {
        return OpenAPIOperation::post('/auth/signout')
            ->tags(['Auth'])
            ->summary('Signout')
            ->description('Signout description');
    }
}
