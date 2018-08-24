<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCredit extends Model
{
    protected $fillable = [
        'id',
        'value',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
