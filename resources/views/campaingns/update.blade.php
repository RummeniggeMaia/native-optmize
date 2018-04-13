@extends('layouts.template')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="">Editar Campanha</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-power-switch"></i>Editar <b>Campanha</b><br><small>Este é seu painel, cuide bem dele :)</small>
            </h1>
        </div>
    </div>
</div>
{!! Form::model($campaingn,['method' => 'patch','route'=>['campaingns.update',$campaingn->id]]) !!}
<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    {!! Form::label('Name', 'Nome:') !!}
    {!! Form::text('name',null,['class'=>'form-control']) !!}
    @if ($errors->has('name'))
        <span class="help-block">
            <strong>{{ $errors->first('name') }}</strong>
         </span>
    @endif
</div>
<div class="form-group {{ $errors->has('brand') ? ' has-error' : '' }}">
    {!! Form::label('Brand', 'Marca:') !!}
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
