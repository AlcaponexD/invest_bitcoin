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

    public function volume()
    {
        $volume = TransactionHistoric::where('type','buy')->whereDate('created_at', Carbon::now())->get();
        return $volume;
    }
}