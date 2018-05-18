<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = array();
        return view('payments.index', compact('payments'));
    }

    public function indexDataTable()
    {
        $payments = DB::table('payments')->where('user_id', Auth::id())->get();
        return Datatables::of($payments)//->make(true);
            ->editColumn('created_at', function($payment){
                return date('d-m-Y H:i', strtotime($payment->created_at));
            })
            ->editColumn('payment_form', function($payment){
                return $payment->payment_form == 1 
                    ? "Cartão de créditos" 
                    : "Boleto";
            })
            ->editColumn('brute_value', function($payment){
                return "R$ " . number_format($payment->brute_value, 2);
            })
            ->editColumn('paid_value', function($payment){
                return "R$ " . number_format($payment->paid_value, 2);
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
            ->rawColumns(['status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('payments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = $request->all();
        $post['status'] = Payment::STATUS_WAITING;
        $post['user_id'] = Auth::id();
        $payment = Payment::create($post);
        return redirect('payments')->with('success', 'Pagamento solicitado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = Payment::find($id);
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payment = Payment::find($id);
        return view('payments.update', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = $request->all();
        $payment = Payment::find($id);
        $payment->update($post);
        return redirect('payments');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment = Payment::find($id);
        $payment->delete();
        return redirect('payments');
    }
}
