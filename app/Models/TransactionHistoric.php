<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class TransactionHistoric extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
        'btc_price',
        'amount'
    ];

    /**
     * @var string
     */
    protected $table = 'historic_transactions';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsTo(User::class);
    }

}
