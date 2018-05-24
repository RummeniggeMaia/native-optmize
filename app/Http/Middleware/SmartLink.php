<?php

namespace App\Http\Middleware;

use App\Click;
use App\User;
use App\Widget;
use App\WidgetLog;
use Carbon\Carbon;
use Closure;

class SmartLink
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $query = $request->query();
        if (!isset($query['wg']) || 
            !isset($query['source'])) {
            return response()->json("invalid request", 400);
        }
        $widget = Widget::where('hashid', $query['wg'])->first();
        if ($widget) {
            Click::create(array(
                'click_id' => hash("sha256", Carbon::now()->toDateTimeString()),
                'widget_id' => $widget->id,
            ));
            // $widget->increment('impressions');
            $widgetLog = WidgetLog::where('widget_id', $widget->id)
                ->whereDate('created_at', Carbon::today()->toDateString())->first();
            if ($widgetLog) {
                $widgetLog->increment('clicks');
            } else {
                WidgetLog::create([
                    'clicks' => 1,
                    'widget_id' => $widget->id,
                ]);
            }
            return response()->json('ok', 200);
        } else {
            return response()->json('invalid input', 400);
        }
    }
}
