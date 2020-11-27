<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;


class Wallets extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'brl_amount',
        'btc_amount'
    ];

    /**
     * @var string
     */
    protected $table = 'wallets';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param $value
     */
    public function setBrlAmountAttribute($value)
    {
        $this->attributes['brl_amount'] = Crypt::encrypt($value);
    }

    /**
     * @param $value
     */
    public function setBtcAmountAttribute($value)
    {
        $this->attributes['btc_amount'] = Crypt::encrypt($value);
    }

    /**
     * @param $value
     */
    public function getBtcAmountAttribute($value)
    {
        return Crypt::decrypt($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getBrlAmountAttribute($value)
    {
        return Crypt::decrypt($value);
    }

}
