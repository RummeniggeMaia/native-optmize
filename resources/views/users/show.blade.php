@extends('layouts/template')
@section('title', 'Usuários')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="{{ route('users') }}">Lista de Usuários</a></li>
    <li><a href="">Exibir Usuários</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-lock"></i>Exibir <b>Usuários</b>
            </h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="block">

            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input title="Nome"  type="text" class="form-control input-lg" id="name" placeholder="{{ $user->name }}" readonly>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    <input title="E-mail" type="text" class="form-control input-lg" id="email" placeholder="{{ $user->email }}" readonly>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-skype"></i></span>
                    <input title="Skype"  type="text" class="form-control input-lg" id="skype" placeholder="{{ $user->skype }}" readonly>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-whatsapp"></i></span>
                    <input title="Telefone/Whatsapp" type="text" class="form-control  input-lg" id="phone" placeholder="{{ $user->phone }}" readonly>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                    <input title="Tarifa em % dos revenues" type="text" class="form-control  input-lg" id="taxa" placeholder="{{ $user->taxa * 100 }}" readonly>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                    <input title="Revenue"  style="background-color: white" type="text" class="form-control input-lg" id="revenue" placeholder="{{ number_format(($user->revenue), 2) }}" readonly>
                </div>
            </div>
            {!! Form::model($user,['class'=>'form-bordered', 'method' => 'patch','route'=>['users.payment',$user->id]]) !!}
            <div class="form-group {{ $errors->has('payment') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                    {!! Form::text('payment',null,['id'=>'payment', 'class'=>'form-control input-lg', 'placeholder' => 'Efetuar pagamento']) !!}
                </div>
                @if ($errors->has('payment'))
                <span class="help-block">
                    <strong>{{ $errors->first('payment') }}</strong>
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
