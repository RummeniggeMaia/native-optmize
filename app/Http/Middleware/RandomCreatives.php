<?php

namespace App\Http\Middleware;

use Closure;
use App\Creative;
use App\Widget;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RandomCreatives {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $query = $request->query();
        if (!isset($query['wg'])) {
            return response()->json("invalid request", 400);
        }
        $widget = Widget::where('hashid', $query['wg'])->first();
        if ($widget) {
            $creatives = Creative::all(
                            'id', 'hashid', 'name', 'brand', 'url', 'image');
            if (count($creatives) > $widget->quantity) {
                $creatives = $creatives->random($widget->quantity);
            }
            $params = array(
                '[click_id]',
                '[widget_id]',
                '[creative_id]',
                '[image]',
                '[headline]'
            );
            foreach ($creatives as $creative) {
                $cId = Hash::make($creative->name
                                . "hashid"
                                . Carbon::now()->toDateTimeString());
                $creative['c_id'] = $cId;
                $fields = array(
                    $cId,
                    $widget->id,
                    $creative->id,
                    url('/') . '/' . $creative->image,
                    urlencode($creative->name)
                );
                $creative->url = str_replace($params, $fields, $creative->url);
            }
            return response()->json($creatives);
        } else {
            return response()->json("not found", 404);
        }
    }

}
