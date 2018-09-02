<?php

namespace App\Http\Middleware;

use App\Widget;
use Closure;
use Illuminate\Http\Response;

class Preroll
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
            return response(view('comum/notfound'));
        }
        $query = $request->query();
        $widget = Widget::where('hashid', $query['wg'])->first();
        $version = md5(time());
        if ($widget) {
            $video_request = json_decode($this->curl_get_request(url('random_creatives?wg=' . $widget->hashid)));
            header('location:'.$video_request[0]->image);
            exit;
        } else {
            return response(view('comum/notfound'));
        }
    }


    function curl_get_request($url_string = '')
    {
        $user_agent = 'Linux / Firefox 44: Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:44.0) Gecko/20100101 Firefox/44.0';
        $url_string = trim($url_string);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url_string,
            CURLOPT_CONNECTTIMEOUT => 60,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT      => $user_agent,
            CURLOPT_FOLLOWLOCATION => true
        ));
        $resp = curl_exec($curl);
        curl_close($curl);
        return $resp;
    }
}
