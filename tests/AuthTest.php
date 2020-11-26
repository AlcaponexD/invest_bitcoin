<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * Test login user
     */
    public function testLoginUser()
    {

      $factory = new \Database\Factories\UserFactory();
      $user = \App\Models\User::create($factory->definition());

        $response = $this->post('/v1/login',[
            'email' => $user->email,
            'password' => '123456'
        ]);
        $response->assertResponseStatus(200);
        $response->arrayHasKey('access_token');
        $response->arrayHasKey('token_type');
        $response->arrayHasKey('expires_in');
    }

    /**
     * Test login user error
     * expected 401
     */
    public function testLoginUserError()
    {

        $factory = new \Database\Factories\UserFactory();
        $user = \App\Models\User::create($factory->definition());

        $response = $this->post('/v1/login',[
            'email' => $user->email,
            'password' => '1234567'
        ]);
        $response->assertResponseStatus(401);
        $response->arrayHasKey('error');
    }

}
