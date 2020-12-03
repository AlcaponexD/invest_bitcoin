<?php
/**
 * Created by PhpStorm.
 * User: AlcaponexD
 * Date: 02/12/2020
 * Time: 01:16
 */

namespace App\Services;


use App\Models\TransactionHistoric;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HistoricService
{
    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }

    /**
     * @param $request
     * @return mixed
     */
    public function extract($request)
    {
        $interval = $request->interval ?? 90;
        $extract = TransactionHistoric::where('user_id',$this->user->id)->whereDate('created_at', '>', Carbon::now()->subDays($interval))->get();

        return $extract;
    }

    /**
     * @return mixed
     */
    public function volume()
    {
        $volume_sell = TransactionHistoric::whereDate('created_at', Carbon::now())->where('type','sell')->select(DB::raw('sum(btc_quantity) as btc_sell'))->first();
        $volume_buyed = TransactionHistoric::whereDate('created_at', Carbon::now())->where('type','buy')->select(DB::raw('sum(btc_quantity) as btc_buy'))->first();
        return [
            $volume_sell->btc_sell,
            $volume_buyed->btc_buy
        ];
    }

}