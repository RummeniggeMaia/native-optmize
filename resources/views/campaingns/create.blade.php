@extends('layouts.template')

@section('content')
{!! Form::open(['url' => 'campaingns']) !!}
<h1>Criar Campaingn</h1>
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
                    value="{{ $creative->id }}">
                {{ $creative->name}},
                {{ $creative->url}}
            </option>
        @endforeach
    </select>
</div>
<div class="form-group">
    {!! Form::submit('Salvar', ['class' => 'btn btn-primary form-control', 'name' => 'salvar']) !!}
</div>
{!! Form::close() !!}

@stop
