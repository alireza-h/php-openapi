<?php

namespace AlirezaH\OpenApi\Document\OperationGenerator;

use AlirezaH\OpenApi\Lib\OpenAPIOperation;
use AlirezaH\OpenApi\Lib\OpenAPIOperationGenerator;
use AlirezaH\OpenApi\Lib\OpenAPIRequestBody;
use AlirezaH\OpenApi\Lib\OpenAPIResponse;

class AuthOpenAPIOperationGenerator extends OpenAPIOperationGenerator
{
    public function signup(): OpenAPIOperation
    {
        return OpenAPIOperation::post('/auth/signup')
            ->tags(['Auth'])
            ->summary('Signup')
            ->description('Signup description')
            ->requestBody(
                OpenAPIRequestBody::create()
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
                OpenAPIResponse::create()
                    ->example(
                        [
                            'data' => [],
                            'message' => null
                        ]
                    )
            );
    }

    public function confirm(): OpenAPIOperation
    {
        return OpenAPIOperation::put('/auth/confirm')
            ->tags(['Auth'])
            ->summary('ConfirmSignup')
            ->description('Confirm signup description')
            ->requestBody(
                OpenAPIRequestBody::create()
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
                OpenAPIResponse::create()
                    ->example(
                        [
                            'data' => [],
                            'message' => null
                        ]
                    )
            );
    }

    public function signin(): OpenAPIOperation
    {
        return OpenAPIOperation::post('/auth/signin')
            ->tags(['Auth'])
            ->summary('Signin')
            ->description('Signin description')
            ->requestBody(
                OpenAPIRequestBody::create()
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
                OpenAPIResponse::create()
                    ->description('Success Signin')
                    ->header('Content-Type', 'string', 'application/json')
                    ->example(
                        [
                            'accessToken' => '_token',
                            'tokenType' => 'bearer',
                            'expiresIn' => 3600
                        ]
                    )
            )
            ->response(
                OpenAPIResponse::create()
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

    public function signout(): OpenAPIOperation
    {
        return OpenAPIOperation::post('/auth/signout')
            ->tags(['Auth'])
            ->summary('Signout')
            ->description('Signout description');
    }
}
