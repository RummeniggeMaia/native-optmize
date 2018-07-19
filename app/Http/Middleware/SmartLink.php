<?php

namespace App\Http\Middleware;

use App\Click;
use App\User;
use App\Widget;
use App\WidgetLog;
use App\Campaingn;
use App\Creative;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Closure;
use Exception;

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
        if (!isset($query['wg'])) {
            return response()->json("invalid request", 400);
        }
        $widget = Widget::where('hashid', $query['wg'])->first();
        if ($widget && $widget->type_layout == Widget::LAYOUT_S_LINK) {
            try {
                DB::beginTransaction();
                $click = Click::create(array(
                    'click_id' => hash("sha256", Carbon::now()->toDateTimeString()),
                    'widget_id' => $widget->id,
                ));
                $widget->createLog(Widget::LOG_CLI, 1);
                $url = "";
                $campaigns = Campaingn::where(['type_layout' => 2])->get();
                if (count($campaigns) > 0) {
                    $campaign = $campaigns->random();
                    $creative = $campaign->creatives->random();
                    $url = $creative->getURL($click);
                    $campaign->createLog(Campaingn::LOG_CLI, 1);
                } else {
                    throw new Exception();
                }
                DB::commit();
                return redirect()->to($url);
            } catch (Exception $ex) {
                DB::rollBack();
                return response()->json('no campaigns', 500);
            }
        } else {
            return response()->json('invalid input', 400);
        }
    }
}
