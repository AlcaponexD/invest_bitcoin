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
           'btc_quantity' => $btc_purchased,
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

    /**
     * @return array
     */
    public function position()
    {
        $btc_current = $this->coin->current();
        $last = $this->user->historic->last();
        $variation =  ($btc_current['sell']/$last->btc_price - 1) * 100;
        $variation_porcent = $variation * 100;


        return [
            'date_puchased' => $last->created_at,
            'amount_invested' => $last->amount,
            'purchase_btc_price' => $last->btc_price,
            'variation' => (float)number_format($variation_porcent,2,'.',''),
            'amount_actually' => (float)($btc_current['sell'] * $last->btc_quantity)
        ];
    }

    public function sell($amount)
    {
        $amount = new Decimal($amount);
        $btc_current = $this->coin->current();
        $btc_sell = (float)$amount->serialize() / (float)$btc_current['buy'];

        if ($btc_sell > $this->user->wallet->btc_amount)
            return [
                'error' => true,
                'message' => 'Você não tem saldo suficiente para realizar esse saque.'
            ];

        $new_btc = ((float)$this->user->wallet->btc_amount - (float)$btc_sell);
        $new_amount = ((float)$this->user->wallet->brl_amount + (float)$amount->serialize());

        /**
         * Create log
         */
        Log::info('transaction_buy',[
            'old' => [
                'btc' => $this->user->wallet->btc_amount,
                'amount' => $this->user->wallet->brl_amount
            ],
            'new' => [
                'btc' => $new_btc,
                'amount' => $new_amount
            ],
            'user_id' => $this->user->id
        ]);


        $this->user->wallet->btc_amount = $new_btc;
        $this->user->wallet->brl_amount = $new_amount;
        $this->user->wallet->save();

        /**
         * create historic
         */
        TransactionHistoric::create([
            'type' => 'sell',
            'btc_price' => $btc_current['buy'],
            'btc_quantity' => $btc_sell,
            'amount' => $amount->serialize(),
            'user_id' => $this->user->id
        ]);

        /**
         * sendmail
         */
        $mail_data = new \stdClass();
        $mail_data->text = "Você resgatou R$ {$amount->serialize()} e vendeu {$btc_sell} bitcon";
        $mail_data->subject = "Você resgatou valores";
        $mail_data->from_email = "jeison.contas@gmail.com";
        $mail_data->from_name = "Jeison Pedroso";
        $mail_data->to = [
            'email' => $this->user->email,
            'name' => $this->user->name,
            'type' => 'to'
        ];

        dispatch(new SendMail($mail_data));
    }
}