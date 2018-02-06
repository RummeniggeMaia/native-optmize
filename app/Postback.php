<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Postback extends Model {

    protected $fillable = [
        'amt',
        'ip',
        'click_id'
    ];

    public function click() {
        return $this->belongsTo('App\Click');
    }
}
