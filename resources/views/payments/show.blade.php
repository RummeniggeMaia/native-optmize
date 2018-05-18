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
                    <input title="Forma de pagamento"  type="text" class="form-control input-lg" id="payment_form" placeholder="{{ $payment->payment_form == 1 ? 'Cartão de créditos' : 'Boleto' }}" readonly>
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
            {!! Form::model($payment->user,['class'=>'form-bordered', 'method' => 'patch','route'=>['users.payment',$payment->id]]) !!}
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
                <div class="form-group form-actions text-center">
                    {!! Form::submit('PAGAR', ['class' => 'btn btn-md btn-default']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop
