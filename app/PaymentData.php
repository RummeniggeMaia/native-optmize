<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentData extends Model
{

    /**
     * Conta corrente
     */
    const CC = 1;
    /**
     * Conta poupança
     */
    const CP = 2;

    protected $fillable = [
        'paypal',
        'number',
        'agency',
        'type',
        'bank',
        'bank_number',
        'cpf',
        'holder',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
