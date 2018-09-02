@extends('layouts/template')
@section('title', 'Minha Conta')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="#">Minha Conta</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-users"></i>Mudar dados do <b>Usuário</b>
            </h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="block">

            {!! Form::model(Auth::user(),['method' => 'patch','route'=>['auth.update',Auth::user()->id]]) !!}
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="gi gi-user"></i></span>
                    {!! Form::text('name',null,['class'=>'form-control input-lg', 'placeholder'=>'Nome', 'required', 'autofocus']) !!}
                    @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    {!! Form::text('email',null,['class'=>'form-control input-lg', 'readonly', 'placeholder'=>'E-mail']) !!}
                    @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('skype') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-skype"></i></span>
                    {!! Form::text('skype',null,['class'=>'form-control input-lg', 'placeholder'=>'Skype']) !!}
                    @if ($errors->has('skype'))
                    <span class="help-block">
                        <strong>{{ $errors->first('skype') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-whatsapp"></i></span>
                    {!! Form::text('phone',null,['class'=>'form-control input-lg',  'placeholder'=>'Telefone/Whatsapp']) !!}
                    @if ($errors->has('phone'))
                    <span class="help-block">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                    {!! Form::text('revenueAdv', Auth::user()->revenueAdv,['class'=>'form-control input-lg', 'title'=>'Revenue Advertiser', 'readonly']) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                    {!! Form::text('revenue', Auth::user()->revenue,['class'=>'form-control input-lg', 'title'=>'Revenue Publisher', 'readonly']) !!}
                </div>
            </div>
            <div class="form-group form-actions text-center">
                {!! Form::submit('ATUALIZAR', ['class' => 'btn btn-md btn-default']) !!}
            </div>
            <div class="form-group form-actions text-center">
                <a href="{{ route('auth.changePassword')}}" class="btn btn-md btn-default">MUDAR SENHA</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop
