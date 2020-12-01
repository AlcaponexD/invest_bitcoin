<?php
namespace App\Services;
use App\Jobs\SendMail;
use App\Models\TransactionHistoric;
use App\Models\Wallets;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Type\Decimal;

/**
 * Created by PhpStorm.
 * User: AlcaponexD
 * Date: 27/11/2020
 * Time: 01:29
 */
class WalletService
{

    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function deposit($data)
    {
        $amount = new Decimal($data['amount']);

        $wallet = $this->user->wallet;
        $wallet->brl_amount = ((float)$wallet->brl_amount+(float)$amount->serialize());
        $wallet->save();

        /**
         * log
         */
        Log::info('deposit_brl',[
            'old' => (float)$wallet->brl_amount,
            'new' => ((float)$wallet->brl_amount+(float)$amount->serialize()),
            'user_id' => $this->user->id
        ]);

        /**
         * create historic
         */
        TransactionHistoric::create([
            'user_id' => $this->user->id,
            'type' => 'deposit',
            'amount' => $amount->serialize()
        ]);

        /**
         * sendmail
         */
        $mail_data = new \stdClass();
        $mail_data->text = "Você fez um depósito no valor de R$ {$amount->serialize()}";
        $mail_data->subject = "Valor depositado";
        $mail_data->from_email = "jeison.contas@gmail.com";
        $mail_data->from_name = "Jeison Pedroso";
        $mail_data->to = [
                'email' => $this->user->email,
                'name' => $this->user->name,
                'type' => 'to'
            ];

        dispatch(new SendMail($mail_data));

        return $wallet;
    }
    public function balance()
    {
         return [
             'total' =>  $this->user->wallet->brl_amount
         ];
    }
}