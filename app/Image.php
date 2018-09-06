<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'name',
        'original_name',
        'path',
        'impressions',
        'creative_id',
    ];

    public function creative() {
        return $this->belongsTo('App\Creative');
    }
}
