<?php

namespace App\Http\Controllers;

use App\Widget;
use App\Click;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

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
        foreach ($widgets as $widget) {
            foreach ($widget->creativeLogs as $log) {
                $clicks = Click::with(['postback'])->where([
                            'creative_id' => $log->creative->id,
                            'widget_id' => $widget->id
                        ])->get();
                $log['revenues'] = round(
                        ($clicks->sum('postback.amt') / 2), 2, PHP_ROUND_HALF_UP
                );
            }
        }
        return view('home', compact('widgets'));
    }

    public function indexDataTable() {
        $widgets = Widget::with(['creativeLogs'])
                        ->where('user_id', Auth::id())->get();
        foreach ($widgets as $widget) {
            $revenues = 0;
            DB::table('clicks')
                    ->where('clicks.widget_id', $widget->id)
                    ->join('postbacks', 'clicks.id', 'postbacks.click_id')
                    ->orderBy('clicks.id')
                    ->chunk(60000, function($clicks) use (&$revenues) {
                        foreach ($clicks as $click) {
                            $revenues += $click->amt;
                        }
                    });
            $widget->revenues = round($revenues / 2, 2, PHP_ROUND_HALF_UP);
            $widget->clicks = $widget->creativeLogs->sum('clicks');
        }
        return Datatables::of($widgets)->make(true);
    }

}
