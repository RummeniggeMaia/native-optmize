<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\WidgetLog;
use Carbon\Carbon;

class Widget extends Model
{

    const LAYOUT_NATIVE = 1;
    const LAYOUT_S_LINK = 2;
    const LAYOUT_BANNER = 3;
    
    const LOG_IMP = 'impressions';
    const LOG_CLI = 'clicks';
    const LOG_REV = 'revenues';

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

    public function widgetCustomization() {
        return $this->hasOne('App\WidgetCustomization');
    }

    public function createLog($property, $value)
    {
        $widgetLog = $this->widgetLogs()
            ->whereDate(
                'created_at',
                Carbon::today()->toDateString()
            )->first();
        if ($widgetLog) {
            $widgetLog->increment($property, $value);
        } else {
            WidgetLog::create([
                $property => $value,
                'widget_id' => $this->id,
            ]);
        }
    }

    public function getBannerDimensions() {
        if ($this->type_layout == 3) {
            return [300, 250];
        } else if ($this->type_layout == 4) {
            return [300, 100];
        } else if ($this->type_layout == 5) {
            return [928, 244];
        } else {
            return [1, 1];
        }
    }
}
