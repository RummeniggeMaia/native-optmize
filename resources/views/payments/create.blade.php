@extends('layouts.template') 
@section('title', 'Pagamentos')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li>
        <a href="{{ route('home') }}">Home</a>
    </li>
    <li>
        <a href="#">Solicitar Pagamentos</a>
    </li>
</ul>
@include('comum.transactions')
<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-money"></i>Solicitar
                <b>Pagamento</b>
            </h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="block">
            {!! Form::open(['url' => 'payments', 'class' => 'form-bordered']) !!}
            <div class="form-group {{ $errors->has('payment_form') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-credit-card"></i>
                    </span>
                    {{ 
                        Form::select('payment_form', 
                        [ 
                            '1'=>"Transferência Bancária", 
                            '2'=>"Paypal",
                        ], 
                        Input::old('payment_form'), ['id'=>'payment_form',
                    'class'=>'selectpicker form-control input-lg', 'required', 'title' => 'Forma de pagamento.']) }}
                </div>
                @if ($errors->has('payment_form'))
                <span class="help-block">
                    <strong>{{ $errors->first('payment_form') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('brute_value') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-dollar"></i>
                    </span>
                    {!! Form::text('brute_value',null,['class'=>'form-control input-lg', 'placeholder' => 'Valor', 'required', 'title' => 'Valor
                    do pagamento.']) !!}
                </div>
                @if ($errors->has('brute_value'))
                <span class="help-block">
                    <strong>{{ $errors->first('brute_value') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group form-actions text-center">
                {!! Form::submit('SOLICITAR', ['class' => 'btn btn-md btn-default']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop
