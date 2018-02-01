<?php

namespace App\Http\Middleware;

use Closure;
use App\Creative;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
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
        if ($request->isMethod('OPTIONS')) {
            return $next($request)
                            ->header('Access-Control-Allow-Origin', '*')
                            ->header('Access-Control-Allow-Methods', 'GET', 'OPTIONS');
        } else {
            $t = $request->route('type') == "3" ? 3 : 6;
            $creatives = Creative::all('id', 'hashid', 'name', 'url', 'image');
            if (count($creatives) > $t) {
                $creatives = $creatives->random($t);
            }
            foreach ($creatives as $creative) {
                $creative['c_id'] = Hash::make(Carbon::now()->toDateTimeString());
            }
            return response()->json($creatives);
        }
    }
}
