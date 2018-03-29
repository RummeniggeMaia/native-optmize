<?php

namespace App\Http\Controllers;

use App\Widget;
use App\Click;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     * creativeLogs.creative.clicks.postback
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $widgets = Widget::with(['creativeLogs.creative'])
                        ->where('user_id', Auth::id())->get();
        foreach ($widget->creativeLogs as $log) {
            $clicks = Click::with(['postback'])->where([
                        'creative_id' => $log->creative->id,
                        'widget_id' => $widget->id
                    ])->get();
            $log['revenues'] = round(
                    ($clicks->sum('postback.amt') / 2), 2, PHP_ROUND_HALF_UP
            );
        }
        return view('home', compact('widgets'));
    }

}
