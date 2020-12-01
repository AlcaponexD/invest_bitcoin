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

    public function __construct(TransactionService $transactionService)
    {
        $this->transaction = $transactionService;
    }

    public function buy(Request $request)
    {
        $this->validate($request,[
            'amount' => ['required','integer',new AmountIsPositive]
        ]);

        return response()->json($this->transaction->buy($request->amount));
    }
}