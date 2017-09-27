<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model {

    protected $fillable = [
        'name',
        'url'
    ];

    public function campaingns() {
        return $this->belongsToMany('App\Campaingn')->withTimestamps();
    }

}
