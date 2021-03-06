@extends('layouts.template')
@section('title', 'Campanhas')

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
                    {!! Form::text('name',null,['id'=>'name','class'=>'form-control input-lg', 'placeholder' => 'Nome']) !!}
                </div>
                @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('type_layout') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-star"></i></span>
                    {{ 
                        Form::select(
                            'type_layout', 
                            [
                                '1'=>'Native',
                                '2'=>'Smart Link',
                                '3'=>'Banner Square (300x250)',
                                '4'=>'Banner Mobile (300x100)',
                                '5'=>'Banner Footer (928x244)',
                                '6'=>'Vídeo',
                            ],
                            Input::old('type_layout'), 
                            [
                                'id' => 'type_layout',
                                'class'=>'selectpicker form-control input-lg',
                                'title'=>'Layout da campanha'
                            ]
                        ) 
                    }}
                </div>
                @if ($errors->has('type_layout'))
                <span class="help-block">
                    <strong>{{ $errors->first('type_layout') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('brand') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="gi gi-tags"></i></span>
                    {!! Form::text('brand',null,['id'=>'brand','class'=>'form-control input-lg', 'placeholder' => 'Brand']) !!}
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
                        Form::select('type', 
                            $types,
                            Input::old('type'), 
                            [
                                'id'=>'drop_type',
                                'placeholder'=>'Selecione um tipo', 
                                'class'=>'selectpicker form-control input-lg', 
                                'required', 
                                'title'=>'Tipo da campanha'
                            ]
                        ) 
                    }}
                </div>
                @if ($errors->has('type'))
                <span class="help-block">
                    <strong>{{ $errors->first('type') }}</strong>
                </span>
                @endif
            </div>
            <div id="cpc_fg" class="form-group {{ $errors->has('cpc') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                    {!! Form::text('cpc',null,['id'=>'cpc','class'=>'form-control input-lg', 'placeholder' => 'CPC Mínimo: 0.001', 'title'=>'Custo por CLICK']) !!}
                </div>
                @if ($errors->has('cpc'))
                <span class="help-block">
                    <strong>{{ $errors->first('cpc') }}</strong>
                </span>
                @endif
            </div>
            <div id="cpm_fg" class="form-group {{ $errors->has('cpm') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                    {!! Form::text('cpm',null,['id'=>'cpm','class'=>'form-control input-lg', 'placeholder' => 'CPM Mínimo: 0.5', 'title'=>'Custo por MIL']) !!}
                </div>
                @if ($errors->has('cpm'))
                <span class="help-block">
                    <strong>{{ $errors->first('cpm') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('ceiling') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                    {!! Form::text('ceiling',null,['class'=>'form-control input-lg', 'placeholder' => 'Orçamento diário', 'title'=>'Orçamento diário']) !!}
                </div>
                @if ($errors->has('ceiling'))
                <span class="help-block">
                    <strong>{{ $errors->first('ceiling') }}</strong>
                </span>
                @endif
            </div>
            <div style="margin-left: 4%">
                <h5><label>Anúncios:</label></h5>
            </div>
            <div class="form-control {{ $errors->has('creatives') ? ' has-error' : '' }}" style="display: table;border: none">
                <div class="col-xs-5" style="display: table-row;">
                    <select name="from" id="multiselect" class="form-control" size="8" multiple="multiple">
                        @foreach($creatives as $creative)
                        <option title="{{ $creative->name }}"
                                value="{{ $creative->id }}"
                                {{ (collect(old('creatives'))->contains($creative->id)) ? 'selected':'' }}>
                            {{ $creative->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xs-2">
                    <button type="button" id="multiselect_rightAll" class="btn btn-block"><i class="fa fa-forward"></i></button>
                    <button type="button" id="multiselect_rightSelected" class="btn btn-block"><i class="fa fa-chevron-right"></i></button>
                    <button type="button" id="multiselect_leftSelected" class="btn btn-block"><i class="fa fa-chevron-left"></i></button>
                    <button type="button" id="multiselect_leftAll" class="btn btn-block"><i class="fa fa-backward"></i></button>
                </div>
                <div class="col-xs-5">
                    <select name="creatives[]" id="multiselect_to" class="form-control" size="8" multiple="multiple">
                    </select>
                </div>
                @if ($errors->has('creatives'))
                <span class="help-block">
                    <strong>{{ $errors->first('creatives') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('device') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="hi hi-star"></i></span>
                    {{ 
                        Form::select('device', 
                            ['1' => 'Mobile', '2' => 'Desktop', '3' => 'Ambos'],
                            Input::old('country'), 
                            [
                                'class'=>'selectpicker form-control input-lg', 
                                'required', 
                                'title'=>'Segmentação: Tipo de dispotivo'
                            ]
                        ) 
                    }}
                </div>
                @if ($errors->has('device'))
                <span class="help-block">
                    <strong>{{ $errors->first('device') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('country') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="hi hi-star"></i></span>
                    {{ 
                        Form::select('country', 
                            $countries,
                            Input::old('country') ? Input::old('country') : '76', 
                            [
                                'class'=>'selectpicker form-control input-lg', 
                                'required', 
                                'title'=>'Segmentação: Pais onde será exibida a campanha'
                            ]
                        ) 
                    }}
                </div>
                @if ($errors->has('country'))
                <span class="help-block">
                    <strong>{{ $errors->first('country') }}</strong>
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
<script src="{{ asset('js/multiselect.min.js') }}"></script>
<script type="text/javascript">
    function toggleFields() {
        if (this.value == "CPC") {
            $("#cpm_fg").hide();
            $("#cpc_fg").show();
        }  else if (this.value == "CPM") {
            $("#cpc_fg").hide();
            $("#cpm_fg").show();
        } else {
            $("#cpc_fg").hide();
            $("#cpm_fg").hide();
        }
    }
    $(document).ready(function () {
        $('#multiselect').multiselect();
        $('#type_layout').change(function() {
            this.form.submit();
        });
        
        $('#drop_type').change(toggleFields);
        $('#drop_type').trigger('change');
    });
</script>
@stop
