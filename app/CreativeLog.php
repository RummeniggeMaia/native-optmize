<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreativeLog extends Model
{
    protected $fillable = [
        'clicks',
        'impressions',
        'revenue',
        'counter',
        'creative_id',
        'widget_id',
        'campaingn_id',
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
}
