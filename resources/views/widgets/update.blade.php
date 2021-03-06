@extends('layouts.template')
@section('title', 'Widgets')

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
            <div class="form-group {{ $errors->has('type_layout') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="hi hi-star"></i></span>
                    {{ 
                        Form::select('type_layout', [
                            '1'=>'Native', 
                            '2'=>'Smart Link',
                            '3'=>'Banner Square (300x250)',
                            '4'=>'Banner Mobile (300x100)',
                            '5'=>'Banner Footer (928x244)',
                            '6' => 'Vídeo',
                        ],
                        Input::old('type_layout'), 
                        ['id'=>'drop_layout', 'class'=>'selectpicker form-control input-lg', 'required', 'title' => 'Tipo de layout do Widget.']) 
                    }}
                </div>
                @if ($errors->has('type_layout'))
                <span class="help-block">
                    <strong>{{ $errors->first('type_layout') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('url') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                    {!! Form::text('url',null,['class'=>'form-control input-lg', 'placeholder'=>'URL', 'required']) !!}
                </div>
                @if ($errors->has('url'))
                <span class="help-block">
                    <strong>{{ $errors->first('url') }}</strong>
                </span>
                @endif
            </div>
            <div id="quantity_fg" class="form-group {{ $errors->has('quantity') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="hi hi-star"></i></span>
                    {{ 
                        Form::select(
                        'quantity', 
                        ['1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6], 
                        null, 
                        ['class'=>'selectpicker form-control input-lg']) 
                    }}
                </div>
                @if ($errors->has('quantity'))
                <span class="help-block">
                    <strong>{{ $errors->first('quantity') }}</strong>
                </span>
                @endif
            </div>
            <div id="customization">
                <div class="form-group {{ $errors->has('image_width') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-paint-brush"></i></span>
                        {!! Form::text(
                            'image_width', 
                            ($widget->widgetCustomization ? $widget->widgetCustomization->image_width : '240'),
                            ['class'=>'form-control input-lg', 
                            'title' => 'Largura das imagens dos anúncios']
                        ) !!}
                    </div>
                    @if ($errors->has('image_width'))
                    <span class="help-block">
                        <strong>{{ $errors->first('image_width') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('image_height') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-paint-brush"></i></span>
                        {!! Form::text(
                            'image_height', 
                            ($widget->widgetCustomization ? $widget->widgetCustomization->image_height : '180'),
                            ['class'=>'form-control input-lg', 
                            'title' => 'Altura das imagens dos anúncios']) !!}
                    </div>
                    @if ($errors->has('image_height'))
                    <span class="help-block">
                        <strong>{{ $errors->first('image_height') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('title_color') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-paint-brush"></i></span>
                        {!! Form::color(
                            'title_color', 
                            ($widget->widgetCustomization ? $widget->widgetCustomization->title_color : '#FFFFFF'),
                            ['class'=>'form-control input-lg', 
                            'title' => 'Cor do título dos anúncios']
                        ) !!}
                    </div>
                    @if ($errors->has('title_color'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title_color') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('title_hover_color') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-paint-brush"></i></span>
                        {!! Form::color(
                            'title_hover_color', 
                            ($widget->widgetCustomization ? $widget->widgetCustomization->title_hover_color : '#0000FF'),
                            ['class'=>'form-control input-lg', 'title' => 'Cor do título quando quando o mouse está sobreposto']
                        ) !!}
                    </div>
                    @if ($errors->has('title_hover_color'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title_hover_color') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('text_color') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-paint-brush"></i></span>
                        {!! Form::color(
                            'text_color', 
                            ($widget->widgetCustomization ? $widget->widgetCustomization->text_color : '#FFFFFF'),
                            ['class'=>'form-control input-lg', 'title' => 'Cor da marca/texto dos anúncios']
                        ) !!}
                    </div>
                    @if ($errors->has('text_color'))
                    <span class="help-block">
                        <strong>{{ $errors->first('text_color') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('card_body_color') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-paint-brush"></i></span>
                        {!! Form::color(
                            'card_body_color',
                            ($widget->widgetCustomization ? $widget->widgetCustomization->card_body_color : '#000000'),
                             ['class'=>'form-control input-lg', 'title' => 'Cor de fundo do Card do anúncio']
                        ) !!}
                    </div>
                    @if ($errors->has('card_body_color'))
                    <span class="help-block">
                        <strong>{{ $errors->first('card_body_color') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="form-group form-actions text-center">
                {!! Form::submit('Atualizar', ['class' => 'btn btn-md btn-default']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<script type="text/javascript">
     function toggleFields() {
        if (this.value != 1) {
            $("#quantity_fg").hide();
            $("#input_quantity").prop('selectedIndex', 0);
            $('#customization').hide();
        } else {
            $("#quantity_fg").show();
            $('#customization').show();
        }
    }
    $(document).ready(function () {
        $('#drop_layout').change(toggleFields);
        $('#drop_layout').change();
    });
</script>
@stop
