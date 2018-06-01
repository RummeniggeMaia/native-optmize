@extends('layouts/template')
@section('title', 'Dados Bancários')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="#">Dados Bancários</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-credit-card"></i>Cadastrar <b>Dados Bancários</b>
            </h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="block">
            {!! Form::model(Auth::user()->paymentData,['method' => 'patch','route'=>['auth.updatePaymentData']]) !!}
                <div class="form-group{{ $errors->has('holder') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        {!! Form::text('holder',null,['class'=>'form-control input-lg', 'placeholder'=>'Titular', 'required']) !!}
                        @if ($errors->has('holder'))
                        <span class="help-block">
                            <strong>{{ $errors->first('holder') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('number') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                        {!! Form::text('number',null,['class'=>'form-control input-lg', 'placeholder'=>'Número da conta', 'required']) !!}
                        @if ($errors->has('number'))
                        <span class="help-block">
                            <strong>{{ $errors->first('number') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('cpf') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                        {!! Form::text('cpf',null,['class'=>'form-control input-lg', 'placeholder'=>'CPF', 'required']) !!}
                        @if ($errors->has('cpf'))
                        <span class="help-block">
                            <strong>{{ $errors->first('cpf') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('agency') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-bank"></i></span>
                        {!! Form::text('agency',null,['class'=>'form-control input-lg', 'placeholder'=>'Agência', 'required']) !!}
                        @if ($errors->has('agency'))
                        <span class="help-block">
                            <strong>{{ $errors->first('agency') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-star"></i></span>
                        {{ 
                            Form::select('type', [
                                '1'=>'Conta Corrente', 
                                '2'=>'Conta Poupança',
                            ],
                            Input::old('type'), 
                            ['id'=>'drop_type', 'class'=>'selectpicker form-control input-lg', 'required', 'title' => 'Tipo de conta bancária.']) 
                        }}
                        @if ($errors->has('type'))
                        <span class="help-block">
                            <strong>{{ $errors->first('type') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('bank') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-bank"></i></span>
                        {!! Form::text('bank',null,['class'=>'form-control input-lg', 'placeholder'=>'Banco', 'required']) !!}
                        @if ($errors->has('bank'))
                        <span class="help-block">
                            <strong>{{ $errors->first('bank') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('bank_number') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-bank"></i></span>
                        {!! Form::text('bank_number',null,['class'=>'form-control input-lg', 'placeholder'=>'Número do banco', 'required']) !!}
                        @if ($errors->has('bank_number'))
                        <span class="help-block">
                            <strong>{{ $errors->first('bank_number') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('paypal') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-paypal"></i></span>
                        {!! Form::text('paypal',null,['class'=>'form-control input-lg', 'placeholder'=>'Paypal']) !!}
                        @if ($errors->has('paypal'))
                        <span class="help-block">
                            <strong>{{ $errors->first('paypal') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group form-actions text-center">
                    {!! Form::submit('ATUALIZAR DADOS', ['class' => 'btn btn-md btn-default']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop
