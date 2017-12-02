@extends('layouts.template')

@section('content')
<h1>Criar User</h1>
{!! Form::open(['url' => 'users']) !!}
<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    {!! Form::label('Name', 'Name:') !!}
    {!! Form::text('name',null,['id'=>'name', 'class'=>'form-control']) !!}
    @if ($errors->has('name'))
    <span class="help-block">
        <strong>{{ $errors->first('name') }}</strong>
    </span>
    @endif
</div>
<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
    {!! Form::label('E-mail', 'E-mail:') !!}
    {!! Form::text('email',null,['id'=>'email', 'class'=>'form-control']) !!}
    @if ($errors->has('email'))
    <span class="help-block">
        <strong>{{ $errors->first('email') }}</strong>
    </span>
    @endif
</div>
<div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
    {!! Form::label('Password', 'Password:') !!}
    {!! Form::password('password',['id'=>'password', 'class'=>'form-control']) !!}
    @if ($errors->has('password'))
    <span class="help-block">
        <strong>{{ $errors->first('password') }}</strong>
    </span>
    @endif
</div>
<div class="form-group">
    {!! Form::submit('Salvar', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@stop
