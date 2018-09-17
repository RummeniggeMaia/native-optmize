<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WidgetCustomization extends Model
{
    protected $fillable = [
        'id',
        'image_width',
        'image_height',
        'title_color',
        'title_hover_color',
        'text_color',
        'card_body_color',
        'widget_id',
    ];

    public function widget()
    {
        return $this->belongsTo('App\Widget');
    }
}
