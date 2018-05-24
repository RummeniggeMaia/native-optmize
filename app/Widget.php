<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Widget extends Model
{

    const LAYOUT_NATIVE = 1;
    const LAYOUT_BANNER = 2;
    const LAYOUT_S_LINK = 3;

    protected $fillable = [
        'hashid',
        'name',
        'url',
        'type',
        'type_layout',
        'quantity',
        'user_id',
    ];

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function campaingns()
    {
        return $this->belongsToMany('App\Campaingn')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function creativeLogs()
    {
        return $this->hasMany('App\CreativeLog');
    }

    public function clicks()
    {
        return $this->hasMany('App\Click');
    }

    public function widgetLogs()
    {
        return $this->hasMany('App\WidgetLog');
    }
}
