<?php

namespace App\Http\Controllers;

use App\Click;
use App\Widget;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;

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
        $money = [
            'today' => 0,
            'yesterday' => 0,
            'thisWeek' => 0,
            'lastWeek' => 0,
            'thisMonth' => 0,
            'lastMonth' => 0,
            'thisYear' => 0,
            'lastYear' => 0,
        ];
        if (Auth::user()->hasAnyRole(['admin', 'adver'])) {
            $money = $this->dailyInvestments();
        } else if (Auth::user()->hasRole('publi')) {
            $money = $this->dailyEarnings();
        } else {
            Auth::logout();
            return redirect('login')
                ->with('error'
                    , 'Usuário com perfil inválido.');
        }
        $camps = DB::table('campaingns')
            ->join('users', function($join){
                $join->('users.id', '=', 'campaingns.user_id')
                    ->where()
            })
            ->leftJoin('campaign_logs', 'campaign_logs.campaingn_id', '=', 'campaingns_id')
            ->join('segmentations', 'segmentations.campaingn_id', '=', 'campaingns.id');
        return view('home')->with('money', $money);
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
                DB::raw('FORMAT(SUM(widget_logs.revenues), 4) as revenues'),
                DB::raw('MONTH(widget_logs.created_at) as month')]);
    }

    public function campaignsLineChartData()
    {
        return DB::table('campaign_logs')
            ->join('campaingns', 'campaign_logs.campaingn_id', '=', 'campaingns.id')
            ->where('user_id', Auth::id())
            ->whereYear('campaign_logs.created_at', Carbon::now()->year)
            ->groupBy('month')
            ->get([
                DB::raw('SUM(campaign_logs.impressions) as impressions'),
                DB::raw('SUM(campaign_logs.clicks) as clicks'),
                DB::raw('FORMAT(SUM(campaign_logs.revenues), 4) as revenues'),
                DB::raw('MONTH(campaign_logs.created_at) as month')]);
    }

    public function widgetsDailyLineChartData() {
        return DB::table('widget_logs')
            ->join('widgets', 'widget_logs.widget_id', '=', 'widgets.id')
            ->where('user_id', Auth::id())
            ->whereYear('widget_logs.created_at', Carbon::now()->year)
            ->whereMonth('widget_logs.created_at', Carbon::now()->month)
            ->groupBy('day')
            ->get([
                DB::raw('SUM(widget_logs.impressions) as impressions'),
                DB::raw('SUM(widget_logs.clicks) as clicks'),
                DB::raw('FORMAT(SUM(widget_logs.revenues), 4) as revenues'),
                DB::raw('DAY(widget_logs.created_at) as day')]);
    }

    public function campaignsDailyLineChartData() {
        return DB::table('campaign_logs')
                ->join('campaingns', 'campaign_logs.campaingn_id', '=', 'campaingns.id')
                ->where('user_id', Auth::id())
                ->whereYear('campaign_logs.created_at', Carbon::now()->year)
                ->whereMonth('campaign_logs.created_at', Carbon::now()->month)
                ->groupBy('day')
                ->get([
                    DB::raw('SUM(campaign_logs.impressions) as impressions'),
                    DB::raw('SUM(campaign_logs.clicks) as clicks'),
                    DB::raw('FORMAT(SUM(campaign_logs.revenues), 4) as revenues'),
                    DB::raw('DAY(campaign_logs.created_at) as day')]);
    }

    // public function campaignsByPeriodChart() {

    //     return DB::table('campaign_logs')
    //             ->join('campaingns', 'campaign_logs.campaingn_id', '=', 'campaingns.id')
    //             ->where('user_id', Auth::id())
    //             ->where('campaign_logs.created_at', Carbon::now()->year)
    //             ->whereMonth('campaign_logs.created_at', Carbon::now()->month)
    //             ->groupBy('day')
    //             ->get([
    //                 DB::raw('SUM(campaign_logs.impressions) as impressions'),
    //                 DB::raw('SUM(campaign_logs.clicks) as clicks'),
    //                 DB::raw('FORMAT(SUM(campaign_logs.revenues), 4) as revenues'),
    //                 DB::raw('DAY(campaign_logs.created_at) as day')]);
    // }

    public function dailyEarnings() {
        $earnings = array();
        $earnings['today'] = $this->dateRevenues(
            Carbon::today()->startOfDaY(), 
            Carbon::today()->endOfDay()
        );
        $earnings['yesterday'] = $this->dateRevenues(
            Carbon::today()->startOfDaY()->subDay(), 
            Carbon::today()->endOfDay()->subDay()
        );
        $earnings['thisWeek'] = $this->dateRevenues(
            Carbon::today()->startOfWeek(),
            Carbon::today()->endOfWeek()
        );
        $earnings['lastWeek'] = $this->dateRevenues(
            Carbon::today()->startOfWeek()->subWeek(),
            Carbon::today()->endOfWeek()->subWeek()
        );
        $earnings['thisMonth'] = $this->dateRevenues(
            Carbon::today()->startOfMonth(),
            Carbon::today()->endOfMonth()
        );
        $earnings['lastMonth'] = $this->dateRevenues(
            Carbon::today()->startOfWeek()->subMonth(),
            Carbon::today()->endOfWeek()->subMonth()
        );
        $earnings['thisYear'] = $this->dateRevenues(
            Carbon::today()->startOfYear(),
            Carbon::today()->endOfYear()
        );
        $earnings['lastYear'] = $this->dateRevenues(
            Carbon::today()->startOfYear()->subYear(),
            Carbon::today()->endOfYear()->subYear()
        );
        return $earnings;
    }
    
    public function dailyInvestments() {
        $investments = array();
        $investments['today'] = $this->dateInvestments(
            Carbon::today()->startOfDaY(), 
            Carbon::today()->endOfDay()
        );
        $investments['yesterday'] = $this->dateInvestments(
            Carbon::today()->startOfDaY()->subDay(), 
            Carbon::today()->endOfDay()->subDay()
        );
        $investments['thisWeek'] = $this->dateInvestments(
            Carbon::today()->startOfWeek(),
            Carbon::today()->endOfWeek()
        );
        $investments['lastWeek'] = $this->dateInvestments(
            Carbon::today()->startOfWeek()->subWeek(),
            Carbon::today()->endOfWeek()->subWeek()
        );
        $investments['thisMonth'] = $this->dateInvestments(
            Carbon::today()->startOfMonth(),
            Carbon::today()->endOfMonth()
        );
        $investments['lastMonth'] = $this->dateInvestments(
            Carbon::today()->startOfWeek()->subMonth(),
            Carbon::today()->endOfWeek()->subMonth()
        );
        $investments['thisYear'] = $this->dateInvestments(
            Carbon::today()->startOfYear(),
            Carbon::today()->endOfYear()
        );
        $investments['lastYear'] = $this->dateInvestments(
            Carbon::today()->startOfYear()->subYear(),
            Carbon::today()->endOfYear()->subYear()
        );
        return $investments;
    }

    public function dateRevenues($start, $end) {
        return DB::table('widget_logs')
            ->join('widgets', 'widget_logs.widget_id', '=', 'widgets.id')
            ->where('user_id', Auth::id())
            ->whereBetween('widget_logs.created_at', [$start, $end])
            ->sum('widget_logs.revenues');
    }

    public function dateInvestments($start, $end) {
        return DB::table('campaign_logs')
            ->join('campaingns', 'campaign_logs.campaingn_id', '=', 'campaingns.id')
            ->where('user_id', Auth::id())
            ->whereBetween('campaign_logs.created_at', [$start, $end])
            ->sum('campaign_logs.revenues');
    }

    public function paymentsDataTable() {
        $payments = Payment::with(['user'])->get();
        return Datatables::of($payments)//->make(true);
            ->editColumn('created_at', function($payment){
                return date('d-m-Y H:i', strtotime($payment->created_at));
            })
            ->editColumn('payment_form', function($payment){
                if ($payment->payment_form == 1) {
                    return "Transferência Bancária";
                } else if ($payment->payment_form == 2) {
                    return "Paypal";
                } else if ($payment->payment_form == 3) {
                    return "Pagseguro";
                }
            })
            ->editColumn('brute_value', function($payment){
                return "R$ " . number_format($payment->brute_value, 2);
            })
            ->editColumn('paid_value', function($payment){
                return "R$ " . number_format($payment->paid_value, 2);
            })
            ->editColumn('taxa', function($payment){
                return "R$ " . number_format($payment->brute_value * $payment->user->taxa, 2);
            })
            ->editColumn('liquid_value', function($payment){
                $taxa = $payment->brute_value * $payment->user->taxa;
                if ($payment->status == Payment::STATUS_REVERSED) {
                    return "R$ <s>" . number_format($payment->brute_value - $taxa, 2) . "</s> / R$ 0,00";
                } else {
                    return "R$ " . number_format($payment->brute_value - $taxa, 2);
                }
            })
            ->editColumn('status', function($payment){
                if ($payment->status == Payment::STATUS_PAID) {
                    return view('comum.status_paid');
                } else if ($payment->status == Payment::STATUS_WAITING) {
                    return view('comum.status_waiting')->with(['name' => 'Pagamento']);
                } else if ($payment->status == Payment::STATUS_REVERSED) {
                    return view('comum.status_reversed');
                }
            })
            ->addColumn('show', function($user) {
                return view('comum.button_show', [
                    'id' => $user->id,
                    'route' => 'payments.show'
                ]);
            })
            ->setRowAttr([
                'style' => function($payment) {
                    if ($payment->status == Payment::STATUS_PAID) {
                        return "background: rgba(39, 174, 96, 0.2);border:2px solid #fff !important";
                    } else if ($payment->status == Payment::STATUS_WAITING) {
                        return "background: rgba(230, 126, 34, 0.2);border:2px solid #fff !important";
                    } else if ($payment->status == Payment::STATUS_REVERSED) {
                        return "background: rgba(255, 75, 75, 0.5);border:2px solid #fff !important";
                    }
                },
            ])
            ->rawColumns(['status', 'liquid_value', 'show'])
            ->make(true);
    }
}
