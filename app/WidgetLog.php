<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WidgetLog extends Model
{
    protected $fillable = [
        'clicks',
        'impressions',
        'revenues',
        'widget_id'
    ];

    public function widget() {
        return $this->belongsTo('App\Widget');
    }
}
