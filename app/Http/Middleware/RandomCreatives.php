<?php

namespace App\Http\Middleware;

use Closure;
use App\Creative;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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
            $creatives = Creative::all('hashid', 'name', 'url', 'image');
            if (count($creatives) > 3) {
                $creatives = $creatives->random(3);
            }
            foreach ($creatives as $c) {
                $c['c_id'] = Hash::make(Carbon::now()->toDateTimeString());
            }
            return response()->json($creatives);
        }
    }
}
