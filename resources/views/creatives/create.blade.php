@extends('layouts.template')
@section('title', 'Anúncios')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="">Adicionar Anúncio</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-bullhorn"></i>Adicionar <b>Anúncio</b>
            </h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="block">
            {!! Form::open(['url' => 'creatives', 'files' => true, 'class'=>'form-bordered']) !!}
            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="gi gi-user"></i></span>
                    {!! Form::text('name',null,['id'=>'name', 'class'=>'form-control input-lg', 'placeholder' => 'Nome', 'required']) !!}
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
                            '6'=>'Vídeo',
                        ],
                        Input::old('type_layout'), 
                        ['id'=>'drop_layout', 'class'=>'selectpicker form-control input-lg', 'required', 'title' => 'Tipo de layout do Anúncio.']) 
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
                    {!! Form::text('brand',null,['class'=>'form-control input-lg', 'placeholder' => 'Brand', 'required']) !!}
                </div>
                @if ($errors->has('brand'))
                <span class="help-block">
                    <strong>{{ $errors->first('brand') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('url') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="gi gi-globe"></i></span>
                    {!! Form::text('url',null,['id'=>'url', 'class'=>'form-control input-lg', 'placeholder' => 'URL', 'required']) !!}
                </div>
                @if ($errors->has('url'))
                <span class="help-block">
                    <strong>{{ $errors->first('url') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-check"></i></span>
                    {{ 
                        Form::select(
                        'status',
                        ['1'=>'Ativo','0'=>'Inativo'], 
                        null, 
                        ['class'=>'selectpicker form-control input-lg', 'required']) 
                    }}
                </div>
                @if ($errors->has('status'))
                <span class="help-block">
                    <strong>{{ $errors->first('status') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-image"></i></span>
                    {!! Form::file('image',['class'=>'form-control input-lg', 'placeholder' => 'Imagem', 'accept'=>'.png,.jpg,.gif,.mp4', 'required']) !!}
                </div>
                @if ($errors->has('image'))
                <span class="help-block">
                    <strong>{{ $errors->first('image') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('category') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-list"></i></span>
                    <select id='category'
                            name="category_id"
                            class="selectpicker form-control input-lg"
                            data-live-search="true"
                            title="Nenhuma Category selecionada"
                            data-actions-box="false"
                            data-select-all-text="Marcar todos"
                            data-deselect-all-text="Desmarcar todos">
                        @foreach($categories as $category)
                        <option
                            title="{{$category->name}}"
                            value="{{$category->id}}"
                            {{ (collect(old('category'))->contains($category->id)) ? 'selected':'' }}>
                            {{$category->name}}
                        </option>
                        @endforeach
                    </select>
                    @if ($errors->has('category'))
                    <span class="help-block">
                        <strong>{{ $errors->first('category') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="form-group form-actions text-center">
                {!! Form::submit('Salvar', ['class' => 'btn btn-md btn-default']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop
