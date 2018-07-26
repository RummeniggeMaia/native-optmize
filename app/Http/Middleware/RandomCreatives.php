<?php

namespace App\Http\Middleware;

use App\Campaingn;
use App\CreativeLog;
use App\Widget;
use App\WidgetLog;
use App\User;
use App\Providers\IP2Location;
use Detection\MobileDetect;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RandomCreatives
{

    const DISK = "public";

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // $db = new IP2Location(Storage::disk(self::DISK)->path("IP-COUNTRY-ISP.BIN"),IP2Location::FILE_IO);
        // $user_ip = $request->ip();

        // $records = $db->lookup($user_ip,IP2Location::ALL);

        // $codigopais= $records['countryCode'];
        // $isp = $records['isp'];

        $query = $request->query();
        if (!isset($query['wg'])) {
            return response()->json("invalid request", 400);
        }
        $widget = Widget::where('hashid', $query['wg'])->first();
        if ($widget) {
            $cont = isset($query['cont']) ? $query['cont'] : 0;
            $campaign = $this->getCampaign($cont, $widget->type_layout);
            if (!$campaign) {
                return response()->json("not found campaign", 404);
            }
            $creatives = $campaign->creatives()->get();
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
            }
            $creatives = $creatives->sortBy('ctr')->reverse();
            /** 
             * TODO
             * Os valores no array sao os tipos de banners
             * Caso mude o type para um valor numerico, mudar aqui
             */
            if (count($creatives) > 0) {
                if (in_array($widget->type_layout, [3,4,5])) {
                    $creatives = $creatives->random(1);//slice(0, 1);
                } else {
                    if (count($creatives) > $widget->quantity) {
                        $creatives = $creatives->slice(0, $widget->quantity);
                    }
                }
                $creatives->each(function ($i, $k) use ($widget, $campaign) {
                    $this->impressions($widget->id, $i->id, $campaign->id);
                });
                /**
                 * TODO remover esse increment futuramente,
                 * ja que o log contabiliza as views do widget
                 */
                $widget->increment('impressions');
                $widget->createLog(Widget::LOG_IMP, 1);
            }
            return response()->json(array_values($creatives->toArray()));
        } else {
            return response()->json("not found widget " . $query['wg'], 404);
        }
    }

    private function impressions($widget, $creative, $campaign)
    {
        $creativeLog = CreativeLog::with(['creative', 'widget', 'campaingn'])->where([
            ['creative_id', $creative],
            ['widget_id', $widget],
            ['campaingn_id', $campaign],
        ])->first();
        if (!$creativeLog) {
            CreativeLog::create(array(
                'creative_id' => $creative,
                'widget_id' => $widget,
                'campaingn_id' => $campaign,
                'impressions' => 1,
                'counter' => 1,
            ));
        } else {
            DB::transaction(function () use ($creativeLog) {
                $cpm = $creativeLog->campaingn->cpm;
                if ($creativeLog->campaingn->type == "CPM" && $cpm > 0.0) {

                    if ($creativeLog->counter == 1000) {
                        $revenueP = $cpm * $creativeLog->widget->user->taxa;
                        $revenueAdm = $cpm * (1 - $creativeLog->widget->user->taxa);
                        $campaignLog = $creativeLog->campaingn->todayLog();
                        if ($creativeLog->campaingn->user->revenue_adv - $cpm < 0
                            || $campaignLog->revenues + $cpm > $creativeLog->campaingn->ceiling) {
                            throw new Exception();
                        }
                        $creativeLog->widget->user->increment('revenue', $revenueP);
                        $creativeLog->campaingn->user->decrement('revenue_adv', $cpm);
                        $creativeLog->creative->increment('revenue', $cpm);
                        $creativeLog->increment('revenue', $cpm);
                        $creativeLog->widget->createLog(Widget::LOG_REV, $revenueP);
                        $campaignLog->increment(Campaingn::LOG_REV, $cpm);
                        $admin = User::with(['roles' => function($query) {
                            return $query->where('name', 'admin');
                        }])->first();
                        $admin->increment('revenue_adv', $revenueAdm);
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
        $detect = new MobileDetect;
        $device = $detect->isMobile() ? 1 : 2;

        $db = new IP2Location(Storage::disk('public')->path("IP-COUNTRY-ISP.BIN"),IP2Location::FILE_IO);
        $user_ip = request()->ip();
        /**
         * TODO caso seja acessado do localhost, mudar o ip para o da google.
         * Essa parte funciona apenas para teste.
         */
        $user_ip = $user_ip == '::1' ? '8.8.8.8' : $user_ip;
        $records = $db->lookup($user_ip, IP2Location::ALL);
        $codigopais = $records['countryCode'];

        $campaigns = Campaingn::with(['user', 'campaignLogs'/*, 'segmentation'*/])
            ->whereHas('user', function($q) {
                return $q->where('revenue_adv', '>', 0);
            })
            // ->whereHas('segmentation', function($q) use ($device, $country) {
            //     return $q->where(
            //         ['device' => $device],
            //         ['country' => $codigopais]
            //     );
            // })
            ->where([
                ['type_layout', $type],
                ['status', true],
                ['paused', false],
            ])->get()->sortByDesc(function ($p, $k) {
                return $p->creatives->sum('revenue');
            });
        if (!$campaigns->isEmpty()) {
            $campaign = null;
            if ($cont >= 1 && $cont <= 3) {
                if ($campaigns->count() == 1) {
                    $cont = 1;
                } else if ($campaigns->count() == 2) {
                    if ($cont > 2) {
                        $cont = 2;
                    }
                }
                $campaign = $campaigns->get($cont - 1);
            } else {
                $campaign =  $campaigns->random();
            }
            $campaign->createLog(Campaingn::LOG_IMP, 1);
            return $campaign;
        }
    }

    private function setCTR($creative)
    {
        $clicks = $creative->creativeLogs->sum('clicks');
        $impressions = $creative->creativeLogs->sum('impressions');
        if ($impressions > 0) {
            $creative['ctr'] = $clicks / $impressions * 100;
        } else {
            $creative['ctr'] = 0;
        }
    }
}
