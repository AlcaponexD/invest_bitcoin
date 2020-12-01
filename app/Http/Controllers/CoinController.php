<?php
/**
 * Created by PhpStorm.
 * User: AlcaponexD
 * Date: 26/11/2020
 * Time: 23:59
 */

namespace App\Http\Controllers;


use App\Jobs\SendMail;
use App\Rules\AmountIsPositive;
use App\Services\CoinService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Type\Decimal;

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