@extends('layouts.template')
@section('title', 'Usuários')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="{{ route('users') }}">Lista de Usuários</a></li>
    <li><a href="">Adicionar créditos</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-money"></i>Adicionar crédios ao <b>Usuário</b>
            </h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="block">
            {!! Form::model($user,['method' => 'patch','route'=>['users.apply_credits',$user->id]]) !!}
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input title="Nome"  type="text" class="form-control input-lg" id="name" placeholder="{{ $user->name }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                        <input type="text" value="{{$user->revenue_adv}}" class="form-control input-lg" title="Saldo atual" readonly/>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('revenue_adv') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                        {!! Form::text('revenue_adv',0,['class'=>'form-control input-lg', 'placeholder'=>'Aumentar saldo do Advertiser', 'title'=>'Aumentar saldo do Advertiser']) !!}
                    </div>
                    @if ($errors->has('revenue_adv'))
                    <span class="help-block">
                        <strong>{{ $errors->first('revenue_adv') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group form-actions text-center">
                    {!! Form::submit('Adicionar', ['class' => 'btn btn-md btn-default']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop
