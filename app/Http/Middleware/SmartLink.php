<?php

namespace App\Http\Middleware;

use App\Click;
use App\User;
use App\Widget;
use App\WidgetLog;
use App\Campaingn;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
        if ($widget && $widget->type_layout == Widget::LAYOUT_S_LINK) {
            try {
                DB::beginTransaction();

                Click::create(array(
                    'click_id' => hash("sha256", Carbon::now()->toDateTimeString()),
                    'widget_id' => $widget->id,
                ));
                $widget->createLog(Widget::LOG_CLI, 1);

                DB::commit();
                return response()->json('ok', 200);
            } catch (Exception $ex) {
                DB::rollBack();
                return response()->json('error', 500);
            }
            
            // $campaign = Campaingn::where(['type_layout' => Widget::LAYOUT_S_LINK])
            //     ->inRandomOrder()
            //     ->first();
        } else {
            return response()->json('invalid input', 400);
        }
    }
}
