@extends('layouts.template')

@section('content')
<h1>Atualizar Campaingn</h1>
{!! Form::model($campaingn,['method' => 'patch','route'=>['campaingns.update',$campaingn->id]]) !!}
<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    {!! Form::label('Name', 'Name:') !!}
    {!! Form::text('name',null,['class'=>'form-control']) !!}
    @if ($errors->has('name'))
        <span class="help-block">
            <strong>{{ $errors->first('name') }}</strong>
         </span>
    @endif
</div>
<div class="form-group {{ $errors->has('brand') ? ' has-error' : '' }}">
    {!! Form::label('Brand', 'Brand:') !!}
    {!! Form::text('brand',null,['class'=>'form-control']) !!}
    @if ($errors->has('brand'))
        <span class="help-block">
            <strong>{{ $errors->first('brand') }}</strong>
         </span>
    @endif
</div>
<div class="form-group {{ $errors->has('creatives') ? ' has-error' : '' }}">
    {!! Form::label('Creatives', 'Creatives:') !!}
    <select id="creatives"
            name="creatives[]"
            class="selectpicker form-control"
            data-live-search="true"
            title="Nenhum Creative selecionado"
            data-actions-box="false"
            data-select-all-text="Marcar todos"
            data-deselect-all-text="Desmarcar todos"
            multiple>
        @foreach($creatives as $creative)
            <option title="{{ $creative->name }}"
                    value="{{ $creative->id }}"
                    @if($campaingn->creatives->contains($creative)) selected @endif>
                {{ $creative->name}},
                {{ $creative->url}}
            </option>
        @endforeach
    </select>
    @if ($errors->has('creatives'))
        <span class="help-block">
            <strong>{{ $errors->first('creatives') }}</strong>
         </span>
    @endif
</div>
<div class="form-group">
    {!! Form::submit('Atualizar', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@stop
