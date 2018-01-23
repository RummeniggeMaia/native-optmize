<?php

namespace App\Http\Middleware;
use App\Creative;
use App\Campaingn;
use App\Widget;
use App\CreativeLog;
use Illuminate\Support\Facades\Log;

use Closure;

class Clicks {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if ($request->isMethod('OPTIONS')) {
            return $next($request)
                            ->header('Access-Control-Allow-Origin', '*')
                            ->header('Access-Control-Allow-Methods', 'GET', 'OPTIONS');
        } else {
            if ($request->has(['ct', 'wg'])) {
                if ($request->has('click_id')) {
                    $log = CreativeLog::where('click_id', $request->input('click_id'))->first();
                    if ($log != null) {
                        return response()->json('exists', 409);
                    }
                }
                $creative = Creative::where('hashid', $request->input('ct'))->first();
                //$campaign = Campaingn::where('hashid', $request->input('cp'))->first();
                $widget = Widget::where('hashid', $request->input('wg'))->first();
                if ($creative && $widget) {
                    CreativeLog::create([
                        'click_id' => ($request->has('click_id') ? $request->input('click_id') : null),
                        'creative_id' => $creative->id,
                        'widget_id' => $widget->id
                    ]);
                    return response()->json('ok', 200);
                } else {
                    return response()->json('invalid input', 400);
                }
            } else {
                return response()->json('note found', 404);
            }
        }
    }

}
