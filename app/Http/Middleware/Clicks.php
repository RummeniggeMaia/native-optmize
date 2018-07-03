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
        if ($request->has(['ct', 'wg', 'click_id'])) {
            $creative = Creative::with('user')->where('hashid', $request->input('ct'))
                ->first();
            $widget = Widget::with('user')->where('hashid', $request->input('wg'))
                ->first();
            $campaign = null;

            if ($request->has('cp')) {
                $campaign = Campaingn::where('hashid', $request->input('cp'))->first();
            }
            if ($creative && $widget) {
                /** TODO campanha deve ser obrigatoria na proxima atualizacao.
                 * - Codigo do site nao tem campanha ainda */
                if ($campaign && $campaign->type == "CPC" && $campaign->cpc > 0) {
                    $widget->user->increment(
                        'revenue',
                        $campaign->cpc * $widget->user->taxa);
                    $creative->increment(
                        'revenue',
                        $campaign->cpc * (1 - $widget->user->taxa));
                }
                $log = CreativeLog::with(['creative', 'widget'])->where([
                    ['creative_id', $creative->id],
                    ['widget_id', $widget->id],
                ])->first();
                $click = null;
                if ($log) {
                    if (!Click::where('click_id', $request->input('click_id'))
                        ->exists()) {
                        $click = Click::create(array(
                            'click_id' => $request->input('click_id'),
                            'creative_id' => $creative->id,
                            'widget_id' => $widget->id,
                        ));
                        $log->increment('clicks');
                    } else {
                        return response()->json('exists', 409);
                    }
                } else {
                    return response()->json('not found', 404);
                }
                $widget->createLog(Widget::LOG_CLI, 1);
                return response()->json('ok', 200);
            } else {
                return response()->json('not found', 404);
            }
        } else {
            return response()->json('invalid input', 400);
        }
    }

}
