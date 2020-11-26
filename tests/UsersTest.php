<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UsersTest extends TestCase
{

    /**
     * Test create user API
     */
    public function testCreateUser()
    {

        $payload = [
            'name' => \Illuminate\Support\Str::random('15'),
            'email' => \Illuminate\Support\Str::random('15').'@phpunit.com',
            'password' => \Illuminate\Support\Str::random(6)
        ];

        $response = $this->post('/v1/users',$payload);
        $response->assertResponseStatus(201);
        $response->arrayHasKey('user');
        $response->arrayHasKey('message');

        $user = \App\Models\User::where('email',$payload['email'])->first();
        $user->delete();


    }

    /*
     * Test create user error Validate
     */
    public function testCreateUserError()
    {
        $payload = [
            'name' => \Illuminate\Support\Str::random('15'),
//            'email' => \Illuminate\Support\Str::random('15').'@phpunit.com',
            'password' => \Illuminate\Support\Str::random(6)
        ];

        $response = $this->post('/v1/users',$payload);
        $response->assertResponseStatus(422);
        $response->arrayHasKey('errors');
    }
}
