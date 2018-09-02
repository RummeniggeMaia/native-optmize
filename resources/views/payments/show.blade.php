@extends('layouts/template')
@section('title', 'Pagamentos')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Dashboard</a></li>
    <li><a href="#">Efetuar Pagamento</a></li>
</ul>
<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-money"></i>Informações do <b>Pagamento</b>
            </h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="block">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input title="Data da solicitação"  type="text" class="form-control input-lg" id="created_at" placeholder="{{ date('d-m-Y H:i', strtotime($payment->created_at))}}" readonly>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input title="Nome do Publisher"  type="text" class="form-control input-lg" id="name" placeholder="{{ $payment->user->name }}" readonly>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                    <input title="Forma de pagamento"  type="text" class="form-control input-lg" id="payment_form" placeholder="{{ $payment->payment_form == 1 ? 'Transferência Bancária' : 'Paypal' }}" readonly>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                    <input title="Valor bruto"  type="text" class="form-control input-lg" id="brute_value" placeholder="R$ {{ number_format($payment->brute_value, 2) }}" readonly>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                    <input title="Taxa"  type="text" class="form-control input-lg" id="taxa" placeholder="R$ {{ number_format($payment->user->taxa * $payment->brute_value, 0) }}" readonly>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                    <input title="Valor líquido"  type="text" class="form-control input-lg" id="liquid_value" placeholder="R$ {{ number_format($payment->brute_value - $payment->user->taxa * $payment->brute_value, 2) }}" readonly>
                </div>
            </div>
            @if($payment->user->paymentData)
                @if($payment->payment_form == 1)
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input title="Titular"  type="text" class="form-control input-lg" id="holder" placeholder="{{ $payment->user->paymentData->holder }}" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                        <input title="Número da conta"  type="text" class="form-control input-lg" id="number" placeholder="{{ $payment->user->paymentData->number }}" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                        <input title="CPF"  type="text" class="form-control input-lg" id="cpf" placeholder="{{ $payment->user->paymentData->cpf }}" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-bank"></i></span>
                        <input title="Agência"  type="text" class="form-control input-lg" id="agency" placeholder="{{ $payment->user->paymentData->agency }}" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-star"></i></span>
                        <input title="Tipo de conta"  type="text" class="form-control input-lg" id="type" placeholder="{{ $payment->user->paymentData->type == 1 ? "Conta Corrente" : "Conta Poupança" }}" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-bank"></i></span>
                        <input title="Banco"  type="text" class="form-control input-lg" id="banco" placeholder="{{ $payment->user->paymentData->bank}}" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-bank"></i></span>
                        <input title="Número do banco"  type="text" class="form-control input-lg" id="bank_number" placeholder="{{ $payment->user->paymentData->bank_number}}" readonly>
                        </div>
                    </div>
                @elseif($payment->payment_form == 2)
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-paypal"></i></span>
                        <input title="Paypal"  type="text" class="form-control input-lg" id="paypal" placeholder="{{ $payment->user->paymentData->paypal }}" readonly>
                        </div>
                    </div>
                @endif
            @endif
            {!! Form::model($payment->user,['class'=>'form-bordered', 'method' => 'patch','route'=>['payments.payment',$payment->id]]) !!}
            <div class="form-group {{ $errors->has('info') ? ' has-error' : ''}} ">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    {{ Form::textarea('info', $payment->info, ['class'=>'form-control textarea', 'rows' => 3, 'title' => 'Informações sobre o pagamento',]) }}
                </div>
                @if ($errors->has('info'))
                <span class="help-block">
                    <strong>{{ $errors->first('info') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="hi hi-star"></i></span>
                    {{ 
                        Form::select('status', [
                                '1'=>'PAGAR', 
                                '3'=>'ESTORNAR',
                            ],
                            Input::old('type_layout'), 
                            [
                                'id'=>'drop_layout', 
                                'class'=>'selectpicker form-control input-lg', 
                                'required', 
                                'title' => 'Pagar ou estornar.'
                            ]
                        ) 
                    }}
                </div>
                @if ($errors->has('status'))
                <span class="help-block">
                    <strong>{{ $errors->first('status') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('paid_value') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                    {!! Form::text('paid_value',$payment->paid_value,['id'=>'paid_value', 'class'=>'form-control input-lg', 'placeholder' => 'Efetuar pagamento']) !!}
                </div>
                @if ($errors->has('paid_value'))
                <span class="help-block">
                    <strong>{{ $errors->first('paid_value') }}</strong>
                </span>
                @endif
                @if ($payment->status == 1)
                    
                    <div class="form-group form-actions text-center">
                        <a class="btn btn-md btn-danger"
                            href="{{ route('payments.voucher', $payment->id) }}">
                            <i class="fa fa-file-pdf-o"></i>
                            ENVIAR COMPROVANTE
                        </a>
                    </div>
                @else
                    <div class="form-group form-actions text-center">
                        {!! Form::submit('PAGAR', ['class' => 'btn btn-md btn-default']) !!}
                    </div>
                @endif
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop
