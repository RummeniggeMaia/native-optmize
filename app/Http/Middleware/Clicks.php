<?php

namespace App\Http\Middleware;

use App\Campaingn;
use App\Click;
use App\Creative;
use App\CreativeLog;
use App\User;
use App\Widget;
use App\WidgetLog;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class Clicks
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
        if ($request->has(['ct', 'wg', 'cp', 'click_id'])) {
            $creative = Creative::with('user')->where('hashid', $request->input('ct'))
                ->first();
            $widget = Widget::with('user')->where('hashid', $request->input('wg'))
                ->first();
            $campaign = Campaingn::where('hashid', $request->input('cp'))
                ->first();
            $click = null;
            if ($creative && $widget && $campaign) {
                $creativeLog = CreativeLog::with(['creative', 'widget', 'campaingn'])->where([
                    ['creative_id', $creative->id],
                    ['widget_id', $widget->id],
                    ['campaingn_id', $campaign->id],
                ])->first();
                if ($creativeLog) {
                    $click = Click::where('click_id', $request->input('click_id'))->first();
                    $count_click = Click::where('click_id', $request->input('click_id') )
                        ->where('creative_id', $creative->id)
                        ->count('click_id');
                    if (!$click || $count_click < 3) {
                        $click = Click::create(array(
                            'click_id' => $request->input('click_id'),
                            'creative_id' => $creative->id,
                            'widget_id' => $widget->id,
                        ));
                        $creativeLog->increment('clicks');
                    } else {
                        return response()->json('click exists', 409);
                    }
                } else {
                    return response()->json('not found', 404);
                }
                if ($campaign->type == "CPC" && $campaign->cpc > 0) {
                    try {
                        DB::beginTransaction();
                        $revenueP = $campaign->cpc * $widget->user->taxa;
                        $revenueAdm = $campaign->cpc * (1 - $widget->user->taxa);
                        $campaignLog = $campaign->todayLog();
                        if (!$campaignLog) {
                            $campaign->createLog(Campaingn::LOG_CLI, 1);
                            $campaignLog = $campaign->todayLog();
                        }
                        if ($campaign->user->revenue_adv - $campaign->cpc < 0
                            || $campaignLog->revenues + $campaign->cpc > $campaign->ceiling) {
                            throw new Exception();
                        }
                        $widget->user->increment('revenue', $revenueP);
                        $campaign->user->decrement('revenue_adv', $campaign->cpc);
                        $creative->increment('revenue', $campaign->cpc);
                        $creativeLog->increment('revenue', $campaign->cpc);
                        $widget->createLog(Widget::LOG_REV, $revenueP);
                        $campaignLog->increment(Campaingn::LOG_REV, $campaign->cpc);
                        $admin = User::with(['roles' => function($query) {
                            return $query->where('name', 'admin');
                        }])->first();
                        $admin->increment('revenue_adv', $revenueAdm);
                        DB::commit();
                    } catch (Exception $e) {
                        DB::rollBack();
                        return response()->json('internal error', 500);
                    }
                }
                $widget->createLog(Widget::LOG_CLI, 1);
                $campaign->createLog(Campaingn::LOG_CLI, 1);
                return redirect()->to($creative->getURL($click));
            } else {
                return response()->json('not found', 404);
            }
        } else {
            return response()->json('invalid input', 400);
        }
    }

}
