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
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Type\Decimal;

class WalletController extends Controller
{
    private $wallet;

    /**
     * WalletController constructor.
     * @param WalletService $walletService
     */
    public function __construct(WalletService $walletService)
    {
        $this->wallet = $walletService;
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deposit(Request $request)
    {
        $this->validate($request,[
           'amount' => ['required','integer',new AmountIsPositive]
        ]);

        $deposit = $this->wallet->deposit($request->all());
        return response()->json($deposit);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function balance()
    {
        return response()->json($this->wallet->balance());
    }
}