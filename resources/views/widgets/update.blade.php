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
<div class="form-group">
    {!! Form::label('Type', 'Type:') !!}
    <select name="type" class="selectpicker form-control">
        @foreach([
            '0' => '----------------------',
            '1' => 'Barra Lateral Direita',
            '2' => 'Barra Lateral Esquerda',
            '3' => 'Central'] as $typeId => $type)
            <option value="{{ $typeId}}" @if($widget->type == $typeId) selected @endif>{{ $type }}</option>
        @endforeach
    </select>
</div>
<div class="form-group {{ $errors->has('campaingns') ? ' has-error' : '' }}">
    {!! Form::label('Campaingns', 'Campaingns:') !!}
    <select name="campaingns[]"
            class="selectpicker form-control"
            data-live-search="true"
            title="Nenhuma Campaingn selecionada"
            data-actions-box="false"
            data-select-all-text="Marcar todas"
            data-deselect-all-text="Desmarcar todas"
            multiple>
        @foreach($campaingns as $campaingn)
            <option title="{{ $campaingn->name }}"
                    value="{{ $campaingn->id }}"
                    @if($widget->campaingns->contains($campaingn)) selected @endif>
                {{ $campaingn->name }},
                {{ $campaingn->brand }}
            </option>
        @endforeach
    </select>
    @if ($errors->has('campaingns'))
    <span class="help-block">
        <strong>{{ $errors->first('campaingns') }}</strong>
    </span>
    @endif
</div>
<div class="form-group">
    {!! Form::submit('Atualizar', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@stop