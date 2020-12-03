<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ExtractTest extends TestCase
{

    use DatabaseTransactions;

    public function testExtract()
    {

        $factory = new \Database\Factories\UserFactory();
        $user = \App\Models\User::create($factory->definition());
        $user->wallet()->create([
            'brl_amount' => 400,
            'btc_amount' => 0
        ]);

        $this->post('/v1/login',[
            'email' => $user->email,
            'password' => '123456'
        ]);
        $token = $this->response->json();
        $headers = [
            'Authorization' => "Bearer {$token['access_token']}"
        ];

        $payload = [
            'amount' => 50
        ];

        $this->post(
            "/v1/buy",
            $payload,
            $headers
        );

        $extract = $this->get(
            "/v1/extract?interval=30",
            $headers
        );

        $extract->assertResponseStatus(200);
        $extract->arrayHasKey('type');
        $extract->arrayHasKey('amount');
        $extract->arrayHasKey('btc_price');
        $extract->arrayHasKey('btc_quantity');
        $extract->arrayHasKey('created_at');

    }

    public function testVolume()
    {

        $factory = new \Database\Factories\UserFactory();
        $user = \App\Models\User::create($factory->definition());
        $user->wallet()->create([
            'brl_amount' => 400,
            'btc_amount' => 0.0020565656
        ]);

        $this->post('/v1/login',[
            'email' => $user->email,
            'password' => '123456'
        ]);
        $token = $this->response->json();
        $headers = [
            'Authorization' => "Bearer {$token['access_token']}"
        ];

        $payload = [
            'amount' => 50
        ];

        $this->post(
            "/v1/buy",
            $payload,
            $headers
        );
        $this->post(
            "/v1/sell",
            $payload,
            $headers
        );


        $extract = $this->get(
            "/v1/volume",
            $headers
        );

        $extract->assertResponseStatus(200);
        $extract->arrayHasKey('total_sell');
        $extract->arrayHasKey('total_buy');

    }

}
