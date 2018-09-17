<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Creative extends Model {

    protected $hidden = [
        'id',
        'pivot', 
        'revenue', 
        'ctr',
        'type_layout',
        'status',
        'user_id',
        'category_id',
        'creativeLogs',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'hashid',
        'brand',
        'name',
        'url',
        'image',
        'type_layout',
        'status',
        'user_id',
        'category_id'
    ];

    public function getCTR() {
        $clicks = $this->creativeLogs->sum('clicks');
        $impressions = $this->creativeLogs->sum('impressions');
        return $impressions > 0 ? $clicks / $impressions * 100 : 0;
    }

    public function getURL($click) {
        if (!preg_match('/^https?:\/\//', $this->url)) {
            $this->url = 'http://' . $this->url;
        }
        $fields = array(
            $click->click_id,
            $click->widget->id,
            $this->id,
            urlencode(url('/') . '/' . $this->image),
            urlencode($this->name),
        );
        return str_replace([
                '[click_id]',
                '[widget_id]',
                '[creative_id]',
                '[image]',
                '[headline]',
            ], $fields, $this->url);
    }

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

    public function images() {
        return $this->hasMany('App\Image');
    }
}
