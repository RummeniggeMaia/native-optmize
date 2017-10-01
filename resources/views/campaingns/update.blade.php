@extends('layouts.template')

@section('content')
<h1>Atualizar Campaingn</h1>
{!! Form::model($campaingn,['method' => 'patch','route'=>['campaingns.update',$campaingn->id]]) !!}
<div class="form-group">
    {!! Form::label('Name', 'Name:') !!}
    {!! Form::text('name',null,['class'=>'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('Brand', 'Brand:') !!}
    {!! Form::text('brand',null,['class'=>'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('Creatives', 'Creatives:') !!}
    <select name="creatives[]"
            class="selectpicker form-control"
            data-live-search="true"
            title="Nenhum Creative selecionado"
            data-actions-box="true"
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
</div>
<div class="form-group">
    {!! Form::submit('Atualizar', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@stop
