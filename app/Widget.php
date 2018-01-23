<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{

    protected $fillable = [
        'hashid',
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

    public function creativeLogs() {
        return $this->hasMany('App\CreativeLog');
    }
}
