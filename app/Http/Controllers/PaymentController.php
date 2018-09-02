<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Validator;

class PaymentController extends Controller
{

    const DISK = "public";

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
            ->editColumn('status', function($payment){
                if ($payment->status == Payment::STATUS_PAID) {
                    return view('comum.status_paid');
                } else if ($payment->status == Payment::STATUS_WAITING) {
                    return view('comum.status_waiting')->with(['name' => 'Pagamento']);
                } else if ($payment->status == Payment::STATUS_REVERSED) {
                    return view('comum.status_reversed');
                }
            })
            ->editColumn('info', function($payment){
                return view('comum.info', ['payment' => $payment]);
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
            ->rawColumns(['status', 'info'])
            ->make(true);
    }

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
        // $validator = 
        $validator = $this->validatePayment($post);
        if (!$validator->fails()) {
            $post['status'] = Payment::STATUS_WAITING;
            $post['user_id'] = Auth::id();
            $payment = Payment::create($post);
            if ($payment) {
                Auth::user()->decrement('revenue', $post['brute_value']);
            }
            return redirect()->back()
                ->with('success', 'Pagamento solicitado com sucesso.');
        } else {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
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
        // $post = $request->all();
        // $payment = Payment::find($id);
        // $payment->update($post);
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

    public function voucher($id) {
        $payment = Payment::find($id);
        return view('payments.voucher', compact('payment'));
    }

    public function sendVoucher(Request $request, $id) {
        $post = $request->all();
        $payment = Payment::find($id);
        $validator = Validator::make($post,
            [
                'file_uploaded' => 'required|max:10240|mimes:pdf'
            ],
            [
                'file_uploaded.required' => 'Arquivo inexistente.',
                'file_uploaded.max' => 'Tamanho máximo: 10MB',
                'file_uploaded.mimes' => 'Arquivo não é do tipo .pdf',
            ]
        );
        if ($payment && !$validator->fails() && $request->hasFile('file_uploaded')) {
            $file = $request->file('file_uploaded');
            $fileName = $file->store('pdf/', self::DISK);
            $payment->pdf = $fileName;
            $payment->save();
            return redirect('home')
                ->with('success', 'Comprovante enviado com sucesso.');
        } else {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }
    }

    public function validatePayment($post)
    {
        $mensagens = array(
            'brute_value.required' => 'Insira um valor.',
            'brute_value.number' => 'Não é um número.',
            'brute_value.min' => 'Pagamento mínimo de R$ 100,00',
            'brute_value.max' => 'Pagamento acima do valor disponível.',
        );
        $rules = array(
            'brute_value' => [
                'required',
                'numeric',
                'min:100',
                'max:' . Auth::user()->revenue
            ],
        );
        $validator = Validator::make($post, $rules, $mensagens);
        return $validator;
    }

    /**
     * TODO Mudar essa funcao para o PaymentController
     */
    public function payment(Request $request, $id) {
        $post = $request->only(['paid_value', 'info', 'status']);
        $payment = Payment::with('user')->find($id);
        $v = Validator::make($post, 
            [
                'paid_value' => 'numeric|min:0|max:2147483647',
                'info' => 'max:190',
                'upload_file' => 'mimes:pdf',
                'status' => 'in:1,3',
            ], 
            [
                'paid_value.numeric' => 'Valor não numérico',
                'paid_value.min' => 'Valor abaixo de zero.',
                'paid_value.max' => 'Valor muito alto.',
                'info.max' => 'No máximo 190 caracteres.',
                'status.in' => 'Método de pagamento inválido.',
            ]
        );
        if ($payment && !$v->fails()) {
            if ($payment->status == Payment::STATUS_REVERSED) {
                return redirect()->back()->with('error'
                                        , 'Pagamento já foi estornado.');
            }
            DB::transaction(function() use ($post, $payment) {
                $status = intval($post['status']);
                $payment->paid_value = doubleval($post['paid_value']);
                if ($status == Payment::STATUS_REVERSED) {
                    $payment->user->increment('revenue', $payment->brute_value);
                    $payment->paid_value = 0.0;
                }
                $payment->status = $status;
                $payment->info = $post['info'];
                $payment->save();
            });
            return redirect()->back()->with('success', 
                'Pagamento ' . (Payment::STATUS_REVERSED ? 'estornado' : 'realizado') .' com sucesso.');
        } else {
            return redirect()->back()
                            ->withErrors($v)
                            ->withInput();
        }
    }
}
