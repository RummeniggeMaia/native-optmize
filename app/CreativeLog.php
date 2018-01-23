<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreativeLog extends Model
{
    protected $fillable = [
        'click_id',
        'revenue',
        'creative_id',
        'widget_id',
        'campaingn_id',
    ];
    
    public function creative() {
        return $this->belongsTo('App\User');
    }
    
    public function widget() {
        return $this->belongsTo('App\Widget');
    }
    
    public function campaingn() {
        return $this->belongsTo('App\Campaingn');
    }
}
