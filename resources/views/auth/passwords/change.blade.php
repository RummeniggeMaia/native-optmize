@extends('layouts/template')

@section('content')
<h1>Mudar password</h1>
{!! Form::model(Auth::user(),['method' => 'patch','route'=>['auth.updatePassword',Auth::user()->id]]) !!}
<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
    {!! Form::label('password', 'Password atual:') !!}
    <input id="password" type="password" class="form-control" name="password" required autofocus>
    @if ($errors->has('password'))
    <span class="help-block">
        <strong>{{ $errors->first('password') }}</strong>
    </span>
    @endif
</div>
<div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
    {!! Form::label('new_password', 'Novo password:') !!}
    <input id="new_password" type="password" class="form-control" name="new_password" required autofocus>
    @if ($errors->has('new_password'))
    <span class="help-block">
        <strong>{{ $errors->first('new_password') }}</strong>
    </span>
    @endif
</div>
<div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
    {!! Form::label('new_password_confirmation', 'Confirmar password:') !!}
    <input id="new_password_confirmation" type="password" 
           class="form-control" name="new_password_confirmation" required autofocus>
    @if ($errors->has('new_password'))
    <span class="help-block">
        <strong>{{ $errors->first('new_password') }}</strong>
    </span>
    @endif
</div>
<div class="form-group">
    {!! Form::submit('Mudar', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@stop
