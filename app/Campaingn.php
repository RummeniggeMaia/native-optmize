<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaingn extends Model {

    protected $fillable = [
        'hashid',
        'brand',
        'name',
        'type',
        'user_id',
    ];

    public function creatives() {
        return $this->belongsToMany('App\Creative')->withTimestamps();
    }

    public function widgets() {
        return $this->belongsToMany('App\Widget')->withTimestamps();
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function creativeLog() {
        return $this->hasMany('App\CreativeLog');
    }
}
