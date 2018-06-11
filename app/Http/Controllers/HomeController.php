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
        return view('home')->with('earnings', $this->dailyEarnings());
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
        return $earnings;
    }

    public function dateRevenues($start, $end) {
        return DB::table('widget_logs')
            ->join('widgets', 'widget_logs.widget_id', '=', 'widgets.id')
            ->where('user_id', Auth::id())
            ->whereBetween('widget_logs.created_at', [$start, $end])
            ->sum('widget_logs.revenues');
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
                    return view('comum.status_waiting');
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
