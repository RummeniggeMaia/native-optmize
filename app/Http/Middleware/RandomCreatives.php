<?php

namespace App\Http\Middleware;

use App\Campaingn;
use App\CreativeLog;
use App\Widget;
use App\WidgetLog;
use Carbon\Carbon;
use Closure;
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
                'id', 'hashid', 'name', 'brand', 'url', 'image']);
            $params = array(
                '[click_id]',
                '[widget_id]',
                '[creative_id]',
                '[image]',
                '[headline]',
            );
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
                $creative->url = str_replace($params, $fields, $creative->url);
                $creative->image = url('/') . '/' . $creative->image;
                $this->setCTR($creative);
                $this->impressions($widget, $creative);
                unset($creative['id']);
            }
            $creativesCTR = $this->sortCreatives($creatives);
            if ($widget->type_layout == 1) {
                if (count($creativesCTR) > $widget->quantity) {
                    $creatives = array_slice($creativesCTR->toArray(), 0, $widget->quantity);
                } else {
                    $creatives = $creativesCTR;
                }
            } else {
                $creatives = array_slice($creativesCTR->toArray(), 0, 1);
            }
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
            Log::info($creatives);
            return response()->json($creatives);
        } else {
            return response()->json("not found", 404);
        }
    }

    private function impressions($widget, $creative)
    {
        $log = CreativeLog::with(['creative', 'widget'])->where([
            ['creative_id', $creative->id],
            ['widget_id', $widget->id],
        ])->first();
        if (!$log) {
            CreativeLog::create(array(
                'creative_id' => $creative->id,
                'widget_id' => $widget->id,
                'impressions' => 1,
            ));
        } else {
            $log->increment('impressions');
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

    private function sortCreatives($creatives)
    {
        return $creatives->sort(function ($a, $b) {
            if ($a->ctr < $b->ctr) {
                return 1;
            } else if ($a->ctr > $b->ctr) {
                return -1;
            } else {
                return 0;
            }
        });
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
