<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CryptoTest extends TestCase
{

    use DatabaseTransactions;


    public function testBuyBitcoin()
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

        $buy = $this->post(
            "/v1/buy",
            $payload,
            $headers
        );

        $buy->assertResponseStatus(200);
        $buy->arrayHasKey('btc_total');

    }


    public function testBuyBitcoinError()
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
            'amount' => 500
        ];

        $buy = $this->post(
            "/v1/buy",
            $payload,
            $headers
        );

        $buy->assertResponseStatus(422);
        $buy->arrayHasKey('error');

    }

    public function testSellBitcoin()
    {

        $factory = new \Database\Factories\UserFactory();
        $user = \App\Models\User::create($factory->definition());
        $user->wallet()->create([
            'brl_amount' => 50,
            'btc_amount' => 2
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

        $sell = $this->post(
            "/v1/sell",
            $payload,
            $headers
        );

        $sell->assertResponseStatus(200);
        $sell->arrayHasKey('btc_sell');

    }

    public function testSellBitcoinError()
    {

        $factory = new \Database\Factories\UserFactory();
        $user = \App\Models\User::create($factory->definition());
        $user->wallet()->create([
            'brl_amount' => 50,
            'btc_amount' => 0.00030656
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
            'amount' => 800
        ];

        $sell = $this->post(
            "/v1/sell",
            $payload,
            $headers
        );

        $sell->assertResponseStatus(422);
        $sell->arrayHasKey('error');

    }

    public function testVolume()
    {

        $factory = new \Database\Factories\UserFactory();
        $user = \App\Models\User::create($factory->definition());
        $user->wallet()->create([
            'brl_amount' => 50,
            'btc_amount' => 0.00030656
        ]);

        $this->post('/v1/login',[
            'email' => $user->email,
            'password' => '123456'
        ]);
        $token = $this->response->json();
        $headers = [
            'Authorization' => "Bearer {$token['access_token']}"
        ];

        $sell = $this->get(
            "/v1/volume",
            $headers
        );

        $sell->assertResponseStatus(200);
        $sell->arrayHasKey('variation');

    }

    public function testCurrent()
    {

        $factory = new \Database\Factories\UserFactory();
        $user = \App\Models\User::create($factory->definition());
        $user->wallet()->create([
            'brl_amount' => 50,
            'btc_amount' => 0.00030656
        ]);

        $this->post('/v1/login',[
            'email' => $user->email,
            'password' => '123456'
        ]);
        $token = $this->response->json();
        $headers = [
            'Authorization' => "Bearer {$token['access_token']}"
        ];

        $sell = $this->get(
            "/v1/current",
            $headers
        );

        $sell->assertResponseStatus(200);
        $sell->arrayHasKey('buy');
        $sell->arrayHasKey('sell');

    }

}
