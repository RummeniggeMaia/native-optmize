@extends('layouts.template') @section('title', 'Widgets') @section('content')
<ul class="breadcrumb breadcrumb-top">
    <li>
        <a href="{{ route('home') }}">Home</a>
    </li>
    <li>
        <a href="#">Adicionar Widget</a>
    </li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-power-switch"></i>Adicionar
                <b>Widget</b>
            </h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="block">
            {!! Form::open(['url' => 'widgets', 'class' => 'form-bordered']) !!}
            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="gi gi-user"></i>
                    </span>
                    {!! Form::text('name',null,['class'=>'form-control input-lg', 'placeholder' => 'Nome', 'required', 'title' => 'Nome']) !!}
                </div>
                @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('type_layout') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="hi hi-star"></i>
                    </span>
                    {{ Form::select('type_layout', [ '1'=>'Native', '2'=>'Banner', '3'=>'Smart Link' ], Input::old('type_layout'), ['id'=>'drop_layout',
                    'class'=>'selectpicker form-control input-lg', 'required', 'title' => 'Tipo de layout do Widget.']) }}
                </div>
                @if ($errors->has('type_layout'))
                <span class="help-block">
                    <strong>{{ $errors->first('type_layout') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('url') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-globe"></i>
                    </span>
                    {!! Form::text('url',null,['class'=>'form-control input-lg', 'placeholder' => 'URL', 'required', 'title' => 'URL/Site'])
                    !!}
                </div>
                @if ($errors->has('url'))
                <span class="help-block">
                    <strong>{{ $errors->first('url') }}</strong>
                </span>
                @endif
            </div>
            <div id="quantity_fg" class="form-group {{ $errors->has('quantity') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="hi hi-star"></i>
                    </span>
                    {{ Form::select( 'quantity', ['3'=>3,'4'=>4,'5'=>5,'6'=>6], null, ['class'=>'selectpicker form-control input-lg', 'required',
                    'title' => 'Quantidade de anúncios no Widget.']) }}
                </div>
                @if ($errors->has('quantity'))
                <span class="help-block">
                    <strong>{{ $errors->first('quantity') }}</strong>
                </span>
                @endif
            </div>
            <div id="type_fg" class="form-group {{ $errors->has('type') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="hi hi-star"></i>
                    </span>
                    {{ Form::select('type', [ '1'=>'----------------------', '2'=>'Barra Lateral Direita', '3'=>'Barra Lateral Esquerda', '4'=>'Central'
                    ], Input::old('type'), ['class'=>'selectpicker form-control input-lg', 'required', 'title' => 'Tipo normal
                    do Widget.']) }}
                </div>
                @if ($errors->has('type'))
                <span class="help-block">
                    <strong>{{ $errors->first('type') }}</strong>
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
<script type="text/javascript">
    $(document).ready(function () {
        $('#drop_layout').change(function (e) {
            if (this.value != 1) {
                $("#quantity_fg").hide();
                $("#type_fg").hide();
            } else {
                $("#quantity_fg").show();
                $("#type_fg").show();
            }
        });
    });
</script>
@stop