<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Creative extends Model {

    protected $fillable = [
        'hashid',
        'brand',
        'name',
        'url',
        'image',
        'status',
        'user_id',
        'category_id'
    ];

    public function campaingns() {
        return $this->belongsToMany('App\Campaingn')->withTimestamps();
    }

    public function category() {
        return $this->belongsTo('App\Category');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
    
    public function creativeLogs() {
        return $this->hasMany('App\CreativeLog');
    }
    
    public function clicks() {
        return $this->hasMany('App\Click');
    }
}
