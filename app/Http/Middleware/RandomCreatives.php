<?php

namespace App\Http\Middleware;

use App\Campaingn;
use App\CreativeLog;
use App\Widget;
use App\WidgetLog;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RandomCreatives
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
        if ($widget) {
            $cont = isset($query['cont']) ? $query['cont'] : 0;
            $campaign = $this->getCampaign($cont, $widget->type_layout);
            if (!$campaign) {
                return response()->json("not found", 404);
            }
            $creatives = $campaign->creatives()->get([
                'id', 'hashid', 'name', 'brand', 'url', 'image', 'revenue']);
            foreach ($creatives as $creative) {
                $cId = hash("sha256", $creative->name
                    . "hashid"
                    . Carbon::now()->toDateTimeString());
                $creative['click_id'] = $cId;
                $creative['campaign_id'] = $campaign->hashid;
                $fields = array(
                    $cId,
                    $widget->id,
                    $creative->id,
                    urlencode(url('/') . '/' . $creative->image),
                    urlencode($creative->name),
                );
                $creative->url = str_replace([
                    '[click_id]',
                    '[widget_id]',
                    '[creative_id]',
                    '[image]',
                    '[headline]',
                ], $fields, $creative->url);
                $creative->image = url('/') . '/' . $creative->image;
                $this->setCTR($creative);
                /**
                 * TODO Caso mude o type para um valor numerico, mudar aqui
                 */
                $this->impressions($widget, $creative, $campaign);
            }
            $creatives = $creatives->sortBy('ctr')->reverse()->toArray();
            if ($widget->type_layout == 1) {
                if (count($creatives) > $widget->quantity) {
                    $creatives = array_slice($creatives->toArray(), 0, $widget->quantity);
                }
            } else {
                $creatives = array_slice($creatives->toArray(), 0, 1);
            }
            /**
             * TODO remover esse increment futuramente,
             * ja que o log contabiliza as views do widget
             */
            $widget->increment('impressions');
            $widgetLog = WidgetLog::where('widget_id', $widget->id)
                ->whereDate('created_at', Carbon::today()->toDateString())->first();
            if ($widgetLog) {
                $widgetLog->increment('impressions');
            } else {
                WidgetLog::create([
                    'impressions' => 1,
                    'widget_id' => $widget->id,
                ]);
            }
            return response()->json(array_values($creatives));
        } else {
            return response()->json("not found", 404);
        }
    }

    private function impressions($widget, $creative, $campaign)
    {
        $creativeLog = CreativeLog::with(['creative', 'widget', 'campaingn'])->where([
            ['creative_id', $creative->id],
            ['widget_id', $widget->id],
            ['campaingn_id', $campaign->id],
        ])->first();
        if (!$creativeLog) {
            CreativeLog::create(array(
                'creative_id' => $creative->id,
                'widget_id' => $widget->id,
                'campaingn_id' => $campaign->id,
                'impressions' => 1,
            ));
        } else {
            DB::transaction(function () use ($widget, $creative, $campaign, $creativeLog) {
                if ($campaign->type == "CPM" && $campaign->cpm > 0.0) {
                    if ($creativeLog->counter == 1000) {
                        $revenueP = $campaign->cpm * $widget->user->taxa;
                        $revenueA = $campaign->cpm * (1 - $widget->user->taxa);
                        $widget->user->increment('revenue', $revenueP);
                        $creative->increment('revenue', $revenueA);
                        $creativeLog->increment('revenue', $revenueA);
                        $widgetLog = WidgetLog::where('widget_id', $widget->id)
                            ->whereDate('created_at', Carbon::today()->toDateString())->first();
                        if ($widgetLog) {
                            $widgetLog->increment('revenues', $revenueP);
                        } else {
                            WidgetLog::create([
                                'revenues' => $revenueP,
                                'widget_id' => $widget->id,
                            ]);
                        }
                        $creativeLog->decrement('counter', 1000);
                    }
                    $creativeLog->increment('counter');
                }
                $creativeLog->increment('impressions');
            });
        }
    }

    private function getCampaign($cont, $type)
    {
        $campaigns = Campaingn::with('creatives')->where([
            'type_layout' => $type,
        ])->get()->sortByDesc(function ($p, $k) {
            return $p->revenues();
        });
        if (!$campaigns->isEmpty()) {
            if ($cont >= 1 && $cont <= 3) {
                if ($campaigns->count() == 1) {
                    $cont = 1;
                } else if ($campaigns->count() == 2) {
                    if ($cont > 2) {
                        $cont = 2;
                    }
                }
                return $campaigns->get($cont - 1);
            } else {
                return $campaigns->random();
            }
        }
    }

    private function setCTR($creative)
    {
        $clicks = CreativeLog::where('creative_id', $creative->id)
            ->sum('clicks');
        $impressions = CreativeLog::where('creative_id', $creative->id)
            ->sum('impressions');
        if ($clicks > 0) {
            $creative['ctr'] = $clicks / $impressions * 100;
        } else {
            $creative['ctr'] = 0;
        }
    }
}
