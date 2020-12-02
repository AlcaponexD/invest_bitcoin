<?php
/**
 * Created by PhpStorm.
 * User: AlcaponexD
 * Date: 26/11/2020
 * Time: 23:59
 */

namespace App\Http\Controllers;


use App\Services\CoinService;

class CoinController extends Controller
{
    private $coin;

    /**
     * CoinController constructor.
     * @param CoinService $coinService
     */
    public function __construct(CoinService $coinService)
    {
        $this->coin = $coinService;
        $this->middleware('auth');
    }

    public function current()
    {
        $coin = $this->coin->current();
        if (isset($coin->error))
            return response()->json($coin['message'],404);

        return response()->json($coin);
    }
}