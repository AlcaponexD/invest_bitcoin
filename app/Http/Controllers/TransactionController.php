<?php
/**
 * Created by PhpStorm.
 * User: AlcaponexD
 * Date: 30/11/2020
 * Time: 23:51
 */

namespace App\Http\Controllers;
use App\Rules\AmounMin;
use App\Rules\AmountIsPositive;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private $transaction;

    /**
     * TransactionController constructor.
     * @param TransactionService $transactionService
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transaction = $transactionService;
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buy(Request $request)
    {
        $this->validate($request,[
            'amount' => ['required','integer',new AmountIsPositive,new AmounMin]
        ]);

        $buy = $this->transaction->buy($request->amount);
        if (isset($buy['error']))
            return response()->json($buy,422);

        return response()->json($buy);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sell(Request $request)
    {
        $this->validate($request,[
            'amount' => ['required','integer',new AmountIsPositive,new AmounMin]
        ]);

        $sell = $this->transaction->sell($request->amount);

        if (isset($sell['error']))
            return response()->json($sell,422);

        return response()->json($sell);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function position()
    {
        return response()->json($this->transaction->position());
    }

}