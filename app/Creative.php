<?php

namespace App;

use App\Category;
use Illuminate\Database\Eloquent\Model;

class Creative extends Model {

    protected $fillable = [
        'name',
        'url',
        'image',
        'related_category',
    ];

    public function campaingns() {
        return $this->belongsToMany('App\Campaingn')->withTimestamps();
    }
    
    public function category() {
        return $this->belongsTo('App\Category');
    }
}
