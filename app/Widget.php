<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $fillable = [
        'name',
        'url',
        'type',
        'owner'
    ];

    public function campaingns()
    {
        return $this->belongsToMany('App\Campaingn')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
