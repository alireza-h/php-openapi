<?php

namespace AlirezaH\OpenApi\Document\OperationGenerator;

use AlirezaH\OpenApi\Lib\OpenApiOperation;
use AlirezaH\OpenApi\Lib\OpenApiOperationGenerator;
use AlirezaH\OpenApi\Lib\OpenApiRequestBody;
use AlirezaH\OpenApi\Lib\OpenApiResponse;

class AuthOpenApiOperationGenerator extends OpenApiOperationGenerator
{
    public function signup(): OpenApiOperation
    {
        return OpenApiOperation::post('/auth/signup')
            ->tags(['Auth'])
            ->summary('Signup')
            ->description('Signup description')
            ->requestBody(
                OpenApiRequestBody::create()
                    ->properties(
                        [
                            [
                                'name' => 'email',
                                'type' => 'string',
                                'format' => 'email',
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
                    ->mediaTypeMultipartFormData()
            )
            ->response(
                OpenApiResponse::create()
                    ->example(
                        [
                            'data' => [],
                            'message' => null
                        ]
                    )
            );
    }

    public function confirm(): OpenApiOperation
    {
        return OpenApiOperation::put('/auth/confirm')
            ->tags(['Auth'])
            ->summary('ConfirmSignup')
            ->description('Confirm signup description')
            ->requestBody(
                OpenApiRequestBody::create()
                    ->properties(
                        [
                            [
                                'name' => 'email',
                                'type' => 'string',
                                'format' => 'email',
                                'example' => $this->faker->email,
                                'description' => 'Email',
                            ],
                            [
                                'name' => 'code',
                                'example' => $this->faker->randomNumber(),
                            ]
                        ]
                    )
                    ->mediaTypeXWwwFormUrlencoded()
            )
            ->response(
                OpenApiResponse::create()
                    ->example(
                        [
                            'data' => [],
                            'message' => null
                        ]
                    )
            );
    }

    public function signin(): OpenApiOperation
    {
        return OpenApiOperation::post('/auth/signin')
            ->tags(['Auth'])
            ->summary('Signin')
            ->description('Signin description')
            ->requestBody(
                OpenApiRequestBody::create()
                    ->properties(
                        [
                            [
                                'name' => 'email',
                                'type' => 'string',
                                'format' => 'email',
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
                    ->mediaTypeMultipartFormData()
            )
            ->response(
                OpenApiResponse::create()
                    ->description('Success Signin')
                    ->header('Content-Type', 'string', 'application/json')
                    ->example(
                        [
                            'accessToken' => '_token',
                            'tokenType' => 'bearer',
                            'expiresIn' => 3600
                        ],
                        'application/json',
                        [
                            'type' => 'object',
                            'properties' => [
                                'accessToken' => [
                                    'type' => 'string',
                                    'description' => 'Access token',
                                ],
                                'tokenType' => [
                                    'type' => 'string',
                                    'description' => 'Token type, e.g. bearer',
                                ],
                                'expiresIn' => [
                                    'type' => 'integer',
                                    'description' => 'Token expiration time in seconds',
                                ],
                            ]
                        ]
                    )
            )
            ->response(
                OpenApiResponse::create()
                    ->status(422)
                    ->description('Invalid Credentials')
                    ->header('Content-Type', 'string', 'application/json')
                    ->example(
                        [
                            'message' => 'The given data was invalid.',
                            'errors' => [
                                'email' => [
                                    'Invalid credentials'
                                ]
                            ]
                        ]
                    )
            );
    }

    public function signout(): OpenApiOperation
    {
        return OpenApiOperation::post('/auth/signout')
            ->tags(['Auth'])
            ->summary('Signout')
            ->description('Signout description');
    }
}
