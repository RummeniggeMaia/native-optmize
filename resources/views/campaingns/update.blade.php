@extends('layouts.template')
@section('title', 'Campanhas')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="{{ route('campaingns') }}">Lista de Campanhas</a></li>
    <li><a href="">Editar Campanha</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-bullhorn"></i>Editar <b>Campanha</b>
            </h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="block">
            {!! Form::model($campaingn,['method' => 'patch','route'=>['campaingns.update',$campaingn->id]]) !!}
            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="gi gi-user"></i></span>
                    {!! Form::text('name',null,['class'=>'form-control input-lg', 'placeholder'=>'Nome', 'required']) !!}
                </div>
                @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('brand') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-tags"></i></span>
                    {!! Form::text('brand',null,['class'=>'form-control input-lg', 'placeholder'=>'Nome', 'required']) !!}
                </div>
                @if ($errors->has('brand'))
                <span class="help-block">
                    <strong>{{ $errors->first('brand') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('type') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="hi hi-star"></i></span>
                    {{ 
                        Form::select('type', [
                            'CPA'=>'CPA', 
                            'CPC'=>'CPC'
                        ],
                        Input::old('type'), 
                        ['placeholder'=>'Selecione um tipo', 
                            'class'=>'selectpicker form-control input-lg', 
                            'required', 
                            'title'=>'Tipo da campanha']) 
                    }}
                </div>
                @if ($errors->has('type'))
                <span class="help-block">
                    <strong>{{ $errors->first('type') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('cpc') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                    {!! Form::text('cpc',null,['id'=>'cpc','class'=>'form-control input-lg', 'placeholder' => 'Custo por click', 'required']) !!}
                </div>
                @if ($errors->has('cpc'))
                <span class="help-block">
                    <strong>{{ $errors->first('cpc') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('creatives') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-bullhorn"></i></span>
                    <select id="creatives"
                            name="creatives[]"
                            class="selectpicker form-control"
                            data-live-search="true"
                            title="Selecione um Anúncio"
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
            </div>
            @if ($errors->has('creatives'))
            <span class="help-block">
                <strong>{{ $errors->first('creatives') }}</strong>
            </span>
            @endif
        </div>
        <div class="form-group form-actions text-center">
            {!! Form::submit('Atualizar', ['class' => 'btn btn-md btn-default']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>
</div>
@stop
