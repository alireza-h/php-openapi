<?php

namespace AlirezaH\OpenApiGenerator\Example\EndpointGenerator;


use AlirezaH\OpenApiGenerator\Lib\OpenAPIEndpoint;
use AlirezaH\OpenApiGenerator\Lib\OpenAPIEndpointGenerator;

class ExampleApiAuthOpenAPIEndpointGenerator extends OpenAPIEndpointGenerator
{
    public function signup(): OpenAPIEndpoint
    {
        return OpenAPIEndpoint::of('/auth/signup')
            ->tags(['Auth'])
            ->summary('Signup')
            ->description('Signup description')
            ->post()
            ->formData(
                [
                    [
                        'name' => 'email',
                        'example' => $this->faker->email,
                        'description' => 'Email',
                    ],
                    [
                        'name' => 'password',
                        'example' => $password = $this->faker->password,
                        'description' => 'Password',
                    ],
                    [
                        'name' => 'password_confirmation',
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

    public function confirm(): OpenAPIEndpoint
    {
        return OpenAPIEndpoint::of('/auth/confirm')
            ->tags(['Auth'])
            ->summary('ConfirmSignup')
            ->description('Confirm signup description')
            ->put()
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

    public function signin(): OpenAPIEndpoint
    {
        return OpenAPIEndpoint::of('/auth/signin')
            ->tags(['Auth'])
            ->summary('Signin')
            ->description('Signin description')
            ->post()
            ->formData(
                [
                    [
                        'name' => 'email',
                        'example' => $this->faker->email,
                        'description' => 'Email',
                    ],
                    [
                        'name' => 'password',
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

    public function signout(): OpenAPIEndpoint
    {
        return OpenAPIEndpoint::of('/auth/signout')
            ->tags(['Auth'])
            ->summary('Signout')
            ->description('Signout description')
            ->post();
    }
}
