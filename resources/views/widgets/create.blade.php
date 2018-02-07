@extends('layouts.template')
@section('content')

<h1>Criar Widget</h1>
{!! Form::open(['url' => 'widgets']) !!}
<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    {!! Form::label('Name', 'Name:') !!}
    {!! Form::text('name',null,['class'=>'form-control']) !!}
    @if ($errors->has('name'))
    <span class="help-block">
        <strong>{{ $errors->first('name') }}</strong>
    </span>
    @endif
</div>
<div class="form-group {{ $errors->has('url') ? ' has-error' : '' }}">
    {!! Form::label('URL', 'URL:') !!}
    {!! Form::text('url',null,['class'=>'form-control']) !!}
    @if ($errors->has('url'))
    <span class="help-block">
        <strong>{{ $errors->first('url') }}</strong>
    </span>
    @endif
</div>

<div class="form-group">
    {!! Form::label('Quantity', 'Quantity:') !!}
    <select name="quantity" class="selectpicker form-control">
        @foreach([
        '0' => 3,
        '1' => 4,
        '2' => 6] as $id => $quantity)
        <option value="{{ $id }}">{{ $quantity }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    {!! Form::label('Type', 'Type:') !!}
    <select name="type" class="selectpicker form-control">
        @foreach([
        '0' => '----------------------',
        '1' => 'Barra Lateral Direita',
        '2' => 'Barra Lateral Esquerda',
        '3' => 'Central'] as $id => $type)
        <option value="{{ $id }}">{{ $type }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    {!! Form::submit('Salvar', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@stop
