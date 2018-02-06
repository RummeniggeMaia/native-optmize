<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Click extends Model {

    protected $fillable = [
        'click_id',
        'creative_id',
        'widget_id'
    ];
    
    public function creative() {
        return $this->belongsTo('App\Creative');
    }

    public function widget() {
        return $this->belongsTo('App\Widget');
    }

    public function campaingn() {
        return $this->belongsTo('App\Campaingn');
    }
    
    public function postback() {
        return $this->hasOne('App\Postback');
    }

}
