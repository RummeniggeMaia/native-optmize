@extends('layouts.template')
@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="{{ route('widgets') }}">Lista de Widgets</a></li>
    <li><a href="">Editar Widget</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-power-switch"></i>Editar <b>Widget</b>
            </h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="block">
            {{ Form::model($widget,['method' => 'patch','route'=>['widgets.update',$widget->id]]) }}
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
            <div class="form-group {{ $errors->has('url') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                    {!! Form::text('url',null,['class'=>'form-control input-lg', placeholder='URL', 'required']) !!}
                </div>
                @if ($errors->has('url'))
                <span class="help-block">
                    <strong>{{ $errors->first('url') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('quantity') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="hi hi-star"></i></span>
                    {{ 
                        Form::select(
                        'quantity', 
                        ['3'=>3,'4'=>4,'5'=>5,'6'=>6], 
                        null, 
                        ['class'=>'selectpicker form-control input-lg', 'placeholder'=>'Selecione uma quantidade']) 
                    }}
                </div>
                @if ($errors->has('quantity'))
                <span class="help-block">
                    <strong>{{ $errors->first('quantity') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('type') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="hi hi-star"></i></span>
                    {{ 
                        Form::select('type', [
                            '1'=>'----------------------', 
                            '2'=>'Barra Lateral Direita', 
                            '3'=>'Barra Lateral Esquerda', 
                            '4'=>'Central'
                        ],
                        Input::old('type'), 
                        ['placeholder'=>'Selecione um tipo', 'class'=>'selectpicker form-control input-lg']) 
                    }}
                </div>
                @if ($errors->has('type'))
                <span class="help-block">
                    <strong>{{ $errors->first('type') }}</strong>
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
