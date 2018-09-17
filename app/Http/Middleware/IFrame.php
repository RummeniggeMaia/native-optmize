<?php

namespace App\Http\Middleware;

use App\Widget;
use Closure;
use Illuminate\Http\Response;

class IFrame
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
        if (!$request->query('wg')) {
            return response(view('comum/notfound')->with(['x' => 10]));
        }
        $query = $request->query();
        $widget = Widget::where('hashid', $query['wg'])->first();
        $version = md5(time());
        if ($widget) {
            return response(view('comum/iframe')->with([
                'url' => addslashes(url('/')),
                'version' => $version,
                'widget_hashid' => $widget->hashid,
            ]));
        } else {
            return response(view('comum/notfound'));
        }
    }
}
