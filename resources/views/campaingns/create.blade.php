@extends('layouts.template')

@section('content')
{!! Form::open(['url' => 'campaingns']) !!}
<h1>Criar Campaingn</h1>
<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    {!! Form::label('Name', 'Name:') !!}
    {!! Form::text('name',null,['id'=>'name','class'=>'form-control']) !!}
    @if ($errors->has('name'))
        <span class="help-block">
            <strong>{{ $errors->first('name') }}</strong>
         </span>
    @endif
</div>
<div class="form-group {{ $errors->has('brand') ? ' has-error' : '' }}">
    {!! Form::label('Brand', 'Brand:') !!}
    {!! Form::text('brand',null,['id'=>'brand','class'=>'form-control']) !!}
    @if ($errors->has('brand'))
        <span class="help-block">
            <strong>{{ $errors->first('brand') }}</strong>
         </span>
    @endif
</div>
<div class="form-group {{ $errors->has('creatives') ? ' has-error' : '' }}">
    {!! Form::label('Creatives', 'Creatives:') !!}
    <select id='creatives'
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
                    {{ (collect(old('creatives'))->contains($creative->id)) ? 'selected':'' }}>
                {{ $creative->name }},
                {{ $creative->url }}
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
    {!! Form::submit('Salvar', ['class' => 'btn btn-primary form-control', 'name' => 'salvar']) !!}
</div>
{!! Form::close() !!}

@stop