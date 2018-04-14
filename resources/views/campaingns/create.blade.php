@extends('layouts.template')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="">Adicionar Campanha</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-bullhorn"></i>Adicionar <b>Campanha</b>
            </h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="block">
            {!! Form::open(['url' => 'campaingns', 'class'=>'form-bordered']) !!}
            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="gi gi-user"></i></span>
                    {!! Form::text('name',null,['id'=>'name','class'=>'form-control input-lg', 'placeholder' => 'Nome', 'required']) !!}
                </div>
                @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('brand') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="gi gi-tags"></i></span>
                    {!! Form::text('brand',null,['id'=>'brand','class'=>'form-control input-lg', 'placeholder' => 'Brand', 'required']) !!}
                </div>
                @if ($errors->has('brand'))
                <span class="help-block">
                    <strong>{{ $errors->first('brand') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('creatives') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-bullhorn"></i></span>
                    <select id='creatives'
                            name="creatives[]"
                            class="selectpicker form-control input-lg"
                            data-live-search="true"
                            title="Nenhum Creative selecionado"
                            data-actions-box="false"
                            data-select-all-text="Marcar todos"
                            data-deselect-all-text="Desmarcar todos"
                            multiple
                            required>
                        @foreach($creatives as $creative)
                        <option title="{{ $creative->name }}"
                                value="{{ $creative->id }}"
                                {{ (collect(old('creatives'))->contains($creative->id)) ? 'selected':'' }}>
                            {{ $creative->name }},
                            {{ $creative->url }}
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
                {!! Form::submit('Salvar', ['class' => 'btn btn-md btn-default']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop
