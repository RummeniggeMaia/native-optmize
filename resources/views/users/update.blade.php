@extends('layouts.template')
@section('title', 'Usuários')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="{{ route('users') }}">Lista de Usuários</a></li>
    <li><a href="">Editar Usuário</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-lock"></i>Editar <b>Usuário</b>
            </h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="block">
            {!! Form::model($user,['method' => 'patch','route'=>['users.update',$user->id]]) !!}
            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="gi gi-user"></i></span>
                    {!! Form::text('name',null,['id'=>'name', 'class'=>'form-control input-lg', 'placeholder'=>'Nome', 'required']) !!}
                </div>
                @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="gi gi-envelope"></i></span>
                    {!! Form::text('email',null,['id'=>'email', 'class'=>'form-control input-lg', 'placeholder'=>'E-Mail', 'required']) !!}
                </div>
                @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('skype') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-skype"></i></span>
                    {!! Form::text('skype',null,['id'=>'skype', 'class'=>'form-control input-lg', 'placeholder'=>'Skype', 'required']) !!}
                </div>
                @if ($errors->has('skype'))
                <span class="help-block">
                    <strong>{{ $errors->first('skype') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-whatsapp"></i></span>
                    {!! Form::text('phone',null,['id'=>'phone', 'class'=>'form-control input-lg', 'placeholder'=>'Telefone/Whatsapp', 'required']) !!}
                </div>
                @if ($errors->has('skype'))
                <span class="help-block">
                    <strong>{{ $errors->first('skype') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
                    {!! Form::password('password',['id'=>'password', 'class'=>'form-control input-lg', 'placeholder'=>'Senha']) !!}
                </div>
                @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group form-actions text-center">
                {!! Form::submit('Atualizar', ['class' => 'btn btn-md btn-default']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop
