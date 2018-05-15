<?php

namespace App\Http\Middleware;

use Closure;
use App\Creative;
use App\Click;
use App\Widget;
use App\CreativeLog;
use App\WidgetLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Clicks {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if ($request->has(['ct', 'wg', 'click_id'])) {
            $creative = Creative::where('hashid', $request->input('ct'))
                    ->first(['id']);
            $widget = Widget::where('hashid', $request->input('wg'))
                    ->first(['id']);
            if ($creative && $widget) {
                $widgetLog = WidgetLog::where('widget_id', $click->widget->id)
                    ->whereDate('created_at', Carbon::today()->toDateString())->first();
                if ($widgetLog) {
                    $widgetLog->increment('clicks');
                } else {
                    WidgetLog::create([
                        'clicks' => 1,
                        'widget_id' => $widget->id,
                    ]);
                }
                $log = CreativeLog::with(['creative', 'widget'])->where([
                            ['creative_id', $creative->id],
                            ['widget_id', $widget->id]
                        ])->first();
                if ($log) {
                    if (!Click::where('click_id', $request->input('click_id'))
                                    ->exists()) {
                        Click::create(array(
                            'click_id' => $request->input('click_id'),
                            'creative_id' => $creative->id,
                            'widget_id' => $widget->id
                        ));
                        $log->increment('clicks');
                    } else {
                        return response()->json('exists', 409);
                    }
                } else {
                    return response()->json('not found', 404);
                }
                return response()->json('ok', 200);
            } else {
                return response()->json('not found', 404);
            }
        } else {
            return response()->json('invalid input', 400);
        }
    }

}
