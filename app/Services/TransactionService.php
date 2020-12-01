<?php
/**
 * Created by PhpStorm.
 * User: AlcaponexD
 * Date: 30/11/2020
 * Time: 23:50
 */

namespace App\Services;


use App\Jobs\SendMail;
use App\Models\TransactionHistoric;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Type\Decimal;

class TransactionService
{
    private $coin;
    private $user;

    /**
     * TransactionService constructor.
     * @param CoinService $coinService
     */
    public function __construct(CoinService $coinService)
    {
        $this->user = auth()->user();
        $this->coin = $coinService;
    }

    /**
     * @param $amount
     * @return array
     */
    public function buy($amount)
    {
        $amount = new Decimal($amount);
        if ($this->user->wallet->brl_amount < $amount->serialize())
            return [
                'error' => true,
                'message' => 'Você não tem saldo suficiente para realizar esta compra, faça um depósito'
            ];

        $btc_current = $this->coin->current();
        $btc_purchased = (float)$amount->serialize() / (float)$btc_current['sell'];
        $old_btc_amount = $this->user->wallet->btc_amount;
        $new_btc_amount = $this->user->wallet->btc_amount + $btc_purchased;
        $this->user->wallet->btc_amount = $new_btc_amount;
        $this->user->wallet->save();


        /**
         * Create log
         */
        Log::info('transaction_buy',[
            'old' => (float)$old_btc_amount,
            'new' => (float)$new_btc_amount,
            'user_id' => $this->user->id
        ]);

        /**
         * create historic
         */
       TransactionHistoric::create([
           'type' => 'buy',
           'btc_price' => $btc_current['sell'],
           'amount' => $amount->serialize(),
           'user_id' => $this->user->id
       ]);

        /**
         * sendmail
         */
        $mail_data = new \stdClass();
        $mail_data->text = "Você comprou {$btc_purchased} bitcoin";
        $mail_data->subject = "Você comprou bitcoin";
        $mail_data->from_email = "jeison.contas@gmail.com";
        $mail_data->from_name = "Jeison Pedroso";
        $mail_data->to = [
            'email' => $this->user->email,
            'name' => $this->user->name,
            'type' => 'to'
        ];

        dispatch(new SendMail($mail_data));

        return [
            'purchased' => $btc_purchased,
            'current' => $btc_current['sell'],
            'btc_total' => $this->user->wallet->btc_amount
        ];
    }
}