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
                ->first(['id']);
            $widget = Widget::with('user')->where('hashid', $request->input('wg'))
                ->first(['id']);
            $campaign = null;

            if ($request->has('cp')) {
                $campaign = Campaingn::where('hashid', $request->input('cp'))->first();
            }
            if ($creative && $widget) {
                /** TODO campanha deve ser obrigatoria na proxima atualizacao.
                 * - Codigo do site nao tem campanha ainda 
                 * - Verificar se revenue do cpc é com base na taxa */
                if ($campaign && $campaign->cpc > 0) {
                    $widget->user->increment(
                        'revenue',
                        $campaign->cpc * $widget->user->taxa);
                    $creative->user->increment(
                        'revenue',
                        $campaign->cpc * (1 - $widget->user->taxa));
                }
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
                    ['widget_id', $widget->id],
                ])->first();
                if ($log) {
                    if (!Click::where('click_id', $request->input('click_id'))
                        ->exists()) {
                        Click::create(array(
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
                return response()->json('ok', 200);
            } else {
                return response()->json('not found', 404);
            }
        } else {
            return response()->json('invalid input', 400);
        }
    }

}
