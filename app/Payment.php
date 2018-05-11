<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
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
        'info'
    ];

}
