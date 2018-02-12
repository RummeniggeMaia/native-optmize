@extends('layouts/template')

@section('content')
<h1>Dados da conta</h1>
{!! Form::model(Auth::user(),['method' => 'patch','route'=>['auth.update',Auth::user()->id]]) !!}
<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    {!! Form::label('Name', 'Name:') !!}
    {!! Form::text('name',null,['class'=>'form-control']) !!}
    @if ($errors->has('name'))
    <span class="help-block">
        <strong>{{ $errors->first('name') }}</strong>
    </span>
    @endif
</div>
<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
    {!! Form::label('E-mail', 'E-mail:') !!}
    {!! Form::text('email',null,['class'=>'form-control', 'readonly']) !!}
    @if ($errors->has('email'))
    <span class="help-block">
        <strong>{{ $errors->first('email') }}</strong>
    </span>
    @endif
</div>
<div class="form-group">
    {!! Form::label('Revenue', 'Revenue:') !!}
    {!! Form::text('revenue','R$ '. Auth::user()->revenue,['class'=>'form-control', 'readonly']) !!}
</div>
<div class="form-group">
    {!! Form::submit('Atualizar', ['class' => 'btn btn-primary form-control']) !!}
</div>
<div class="form-group">
    <a href="{{ route('auth.changePassword')}}" class="btn btn-primary form-control">Mudar password</a>
</div>
{!! Form::close() !!}
@stop
