<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\CreativeLog;
use Closure;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if ($request->has('hashid') && $request->has('f')) {
            $widget = DB::table('widgets')
                            ->where('hashid', $request->hashid)->first();
            $log = CreativeLog::find($widget->creative_log);
            if ($request->f == 'view') {
                $log->impressions = $log->impressions + 1;
            } else if ($request->f == 'click') {
                $log->clicks = $log->clicks + 1;
            } else if ($request->f == 'revenue') {
                
            }
            $log->save();
            return $next($request)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET', 'OPTIONS');
        }
        return $next($request);
    }
}
