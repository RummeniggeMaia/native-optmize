<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'fixed',
        'owner'
    ];

    public function creatives() {
        return $this->hasMany('App\Creative');
    }
    
    public function user() {
        return $this->belongsTo('App\User');
    }
    
}
