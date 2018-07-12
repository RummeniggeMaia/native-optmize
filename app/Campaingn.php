<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaingn extends Model {

    const LOG_IMP = 'impressions';
    const LOG_CLI = 'clicks';
    const LOG_REV = 'revenues';

    protected $fillable = [
        'hashid',
        'brand',
        'name',
        'type',
        'type_layout',
        'cpc',
        'cpm',
        'expires_in',
        'paused',
        'status',
        'user_id',
    ];

    // public function revenues() {
    //     return $this->creatives()->sum('revenue');
    // }
    
    public function creatives() {
        return $this->belongsToMany('App\Creative')->withTimestamps();
    }
    
    public function widgets() {
        return $this->belongsToMany('App\Widget')->withTimestamps();
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function creativeLog() {
        return $this->hasMany('App\CreativeLog');
    }

    public function campaignLogs()
    {
        return $this->hasMany('App\CampaignLog');
    }

    public function createLog($property, $value)
    {
        $campaignLog = $this->campaignLogs()
            ->whereDate(
                'created_at',
                Carbon::today()->toDateString()
            )->first();
        if ($campaignLog) {
            $campaignLog->increment($property, $value);
        } else {
            CampaignLog::create([
                $property => $value,
                'campaingn_id' => $this->id,
            ]);
        }
    }
}
