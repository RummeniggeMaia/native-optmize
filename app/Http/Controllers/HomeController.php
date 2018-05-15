<?php

namespace App\Http\Controllers;

use App\Click;
use App\Widget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     * creativeLogs.creative.clicks.postback
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function indexDataTable()
    {
        $widgets = Widget::with(['creativeLogs'])
            ->where('user_id', Auth::id())->get();
        foreach ($widgets as $widget) {
            $widget->revenues = $widget->creativeLogs->sum('revenue');
            $widget->clicks = $widget->creativeLogs->sum('clicks');
        }
        return Datatables::of($widgets)->editColumn('revenues', function ($widget) {
            return 'R$ ' . round($widget->revenues, 2, PHP_ROUND_HALF_UP);
        })->make(true);
    }

    public function widgetsLineChartData()
    {
        return DB::table('widget_logs')
            ->join('widgets', 'widget_logs.widget_id', '=', 'widgets.id')
            ->where('user_id', Auth::id())
            ->whereYear('widget_logs.created_at', Carbon::now()->year)
            ->groupBy('month')
            ->get([
                DB::raw('SUM(widget_logs.impressions) as impressions'),
                DB::raw('SUM(widget_logs.clicks) as clicks'),
                DB::raw('SUM(widget_logs.revenues) as revenues'),
                DB::raw('MONTH(widget_logs.created_at) as month')]);
    }

}
