<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    const STATUS_PAID = 1;
    const STATUS_WAITING = 2;
    const STATUS_REVERSED = 3;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'payment_form',
        'brute_value',
        'paid_value',
        'tax',
        'liquid_value',
        'status',
        'info',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
