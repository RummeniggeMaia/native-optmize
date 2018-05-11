<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;


class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::all();
        return view('payments.index', compact('payments'));
    }

    public function indexDataTable() {
        $payments = DB::table('payments')->get();
        return Datatables::of($payments)->addColumn('edit', function($payment) {
                    return view('comum.button_edit', [
                        'id' => $payment->id,
                        'route' => 'payments.edit'
                    ]);
                })->addColumn('show', function($payment) {
                    return view('comum.button_show', [
                        'id' => $payment->id,
                        'route' => 'payments.show'
                    ]);
                })->addColumn('delete', function($payment) {
                    return view('comum.button_delete', [
                        'id' => $payment->id,
                        'route' => 'payments.destroy'
                    ]);
                })->rawColumns(
                        ['edit', 'show', 'delete']
                )->make(true);
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
        $post['status'] = false;
        $payment = Payment::create($post);
        return redirect('payments')->with('success', 'Pagamento cadastrado com sucesso.');
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
