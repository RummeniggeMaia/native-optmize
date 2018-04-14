<?php

namespace App\Http\Middleware;

use Closure;
use App\Click;
use App\Password;
use App\Creative;
use App\Postback;
use App\Widget;
use App\User;
use App\CreativeLog;
use Illuminate\Support\Facades\Log;

class Postbacks {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $query = $request->query();
        if (isset($query['tid'], $query['amt'], $query['senha'])) {
            $amt = $query['amt'];
            if (is_numeric($amt) && $amt >= 0.0) {
                $pw = Password::where('senha', $query['senha'])->first();
                $click = Click::with(['creative', 'widget.user', 'postback'])
                        ->where('click_id', $query['tid'])->first();
                if ($pw && $click) {
                    if ($click->postback) {
                        return response()->json('conflict', 409);
                    }
//                    $value = round(($amt / 2), 2, PHP_ROUND_HALF_UP);
                    $value = $amt / 2;
                    $click->creative->increment('revenue', $value);
                    $click->widget->user->increment('revenue', $value);
                    $log = CreativeLog::where([
                        'creative_id' => $click->creative->id,
                        'widget_id' => $click->widget->id
                    ]);
                    if ($log) {
                        $log->increment('revenue', $value);
                    } else {
                        response()->json("no register", 400);
                    }
                    Postback::create(array(
                        'ip' => $request->ip(),
                        'amt' => $amt,
                        'click_id' => $click->id
                    ));
                    return response()->json('ok', 200);
                } else {
                    return response()->json("not found", 400);
                }
            }
        }
        return response()->json("invalid request", 400);
    }
}
