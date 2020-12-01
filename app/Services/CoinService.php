<?php
/**
 * Created by PhpStorm.
 * User: AlcaponexD
 * Date: 30/11/2020
 * Time: 22:06
 */

namespace App\Services;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;


class CoinService
{


    const url = 'https://www.mercadobitcoin.net/api/';

    public function current()
    {
        try{
            $client = new Client([
                'base_uri' => self::url
            ]);

            $r = $client->get('BTC/ticker');

            $response = $r->getBody();
            $result = \GuzzleHttp\json_decode($response->getContents());

            return [
                'buy' => $result->ticker->buy,
                'sell' => $result->ticker->sell
            ];

        }catch (GuzzleException  $e){
            $error = [
                'error' => true,
                'message' => $e->getMessage()
            ];
            Log::info('error_api_mercadobitcoin',$error);

            return $error;
        }
    }
}