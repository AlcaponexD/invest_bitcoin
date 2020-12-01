<?php
/**
 * Created by PhpStorm.
 * User: AlcaponexD
 * Date: 30/11/2020
 * Time: 23:51
 */

namespace App\Http\Controllers;
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
            'amount' => ['required','integer',new AmountIsPositive]
        ]);

        return response()->json($this->transaction->buy($request->amount));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function position()
    {
        return response()->json($this->transaction->position());
    }


}