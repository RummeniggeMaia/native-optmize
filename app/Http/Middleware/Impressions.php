<?php

namespace App\Http\Middleware;

use Closure;
use App\Creative;
use App\Widget;
use App\CreativeLog;
use Illuminate\Support\Facades\Log;

class Impressions {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        return response()->json('no content', 204);
//        if ($request->has(['ct', 'wg'])) {
//            $creative = Creative::where('hashid', $request->input('ct'))
//                    ->first(['id']);
//            $widget = Widget::where('hashid', $request->input('wg'))
//                    ->first(['id']);
//            if ($creative && $widget) {
//                $log = CreativeLog::with(['creative', 'widget'])->where([
//                            ['creative_id', $creative->id],
//                            ['widget_id', $widget->id]
//                        ])->first();
//                if (!$log) {
//                    CreativeLog::create(array(
//                        'creative_id' => $creative->id,
//                        'widget_id' => $widget->id,
//                        'impressions' => 1
//                    ));
//                } else {
//                    $log->increment('impressions');
//                }
//                return response()->json('ok', 200);
//            } else {
//                return response()->json('not found', 404);
//            }
//        } else {
//            return response()->json('invalid input', 400);
//        }
    }

}
