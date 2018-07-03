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
use App\WidgetLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
                    /** TODO Adiocionar HALF_UP caso seja necessrio nas proximas atts */
//                    $value = round(($amt / 2), 2, PHP_ROUND_HALF_UP);
                    $value = $amt * $click->widget->user->taxa;
                    try {
                        DB::beginTransaction();
                        $click->creative->increment('revenue', $value);
                        $click->widget->user->increment('revenue', $value);
                        $log = CreativeLog::where([
                            'creative_id' => $click->creative->id,
                            'widget_id' => $click->widget->id
                        ]);
                        if ($log) {
                            $log->increment('revenue', $value);
                            /* TODO - Caso surja um super usuario, atualizar aqui */
                            $adm_value = $amt * (1 - $click->widget->user->taxa);
                            $admin = User::where(['email' => 'admin@admin.in']);
                            $admin->increment('revenue', $adm_value);
                        } else {
                            response()->json("no register", 400);
                        }
                        $click->widget->createLog(Widget::LOG_REV, $value);
                        Postback::create(array(
                            'ip' => $request->ip(),
                            'amt' => $amt,
                            'click_id' => $click->id
                        ));
                        DB::commit();
                    } catch (Exception $e) {
                        DB::rollBack();
                        return response()->json('internal error', 500);
                    }
                    return response()->json('ok', 200);
                } else {
                    return response()->json("not found", 400);
                }
            }
        }
        return response()->json("invalid request", 400);
    }
}
