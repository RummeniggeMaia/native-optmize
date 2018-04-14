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
        return view('home');
    }

    public function indexDataTable() {
        $widgets = Widget::with(['creativeLogs'])
                        ->where('user_id', Auth::id())->get();
        foreach ($widgets as $widget) {
//            $revenues = 0;
//            DB::table('clicks')
//                    ->where('clicks.widget_id', $widget->id)
//                    ->join('postbacks', 'clicks.id', 'postbacks.click_id')
//                    ->orderBy('clicks.id')
//                    ->chunk(60000, function($clicks) use (&$revenues) {
//                        foreach ($clicks as $click) {
//                            $revenues += $click->amt;
//                        }
//                    });
            $widget->revenues = $widget->creativeLogs->sum('revenue');
            $widget->clicks = $widget->creativeLogs->sum('clicks');
        }
        return Datatables::of($widgets)->editColumn('revenues', function($widget) {
                    return 'R$ ' . round($widget->revenues, 2, PHP_ROUND_HALF_UP);
                })->make(true);
    }

}
