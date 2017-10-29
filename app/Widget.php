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
        'creative_log',
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
    
    public function creative_log() {
        return $this->hasOne('App\CreativeLog');
    }
}
