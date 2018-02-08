@extends('layouts.template')
@section('content')
<h1>Atualizar Widget</h1>
{!! Form::model($widget,['method' => 'patch','route'=>['widgets.update',$widget->id]]) !!}
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
<div class="form-group {{ $errors->has('quantity') ? ' has-error' : '' }}">
    {!! Form::label('Quantity', 'Quantity:') !!}
    {{ Form::select('quantity', ['3'=>3,'4'=>4,'6'=>6], null, ['class'=>'selectpicker form-control']) }}
    @if ($errors->has('quantity'))
    <span class="help-block">
        <strong>{{ $errors->first('quantity') }}</strong>
    </span>
    @endif
</div>
<div class="form-group">
    {!! Form::label('Type', 'Type:') !!}
    {{ Form::select(
            'type', 
            [
                '----------------------', 
                'Barra Lateral Direita', 
                'Barra Lateral Esquerda', 
                'Central'
            ],
            null, 
            ['class'=>'selectpicker form-control']) }}
</div>
<div class="form-group">
    {!! Form::submit('Atualizar', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@stop
