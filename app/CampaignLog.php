<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignLog extends Model
{
    protected $fillable = [
        'clicks',
        'impressions',
        'revenues',
        'campaingn_id'
    ];

    public function campaingn() {
        return $this->belongsTo('App\Campaingn');
    }
}
