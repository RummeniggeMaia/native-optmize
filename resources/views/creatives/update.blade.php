@extends('layouts.template')
@section('title', 'Anúncios')

@section('content')

<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="{{ route('creatives') }}">Lista de Anúncios</a></li>
    <li><a href="">Editar Anúncio</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-bullhorn"></i>Editar <b>Anúncio</b>
            </h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="block">
            {!! Form::model($creative,['method' => 'patch','route'=>['creatives.update',$creative->id], 'files' => true]) !!}
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
                            '3'=>'Banner Square (300x250)',
                            '4'=>'Banner Mobile (300x100)',
                            '5'=>'Banner Footer (928x244)',
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
                    <span class="input-group-addon"><i class="fa fa-tags"></i></span>
                    {!! Form::text('brand',null,['class'=>'form-control input-lg', 'placeholder'=>'Marca', 'required']) !!}
                </div>
                @if ($errors->has('brand'))
                <span class="help-block">
                    <strong>{{ $errors->first('brand') }}</strong>
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
            <div class="row">
                <div class="form-group col-sm-6 {{ $errors->has('image') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-image"></i></span>
                        {!! Form::file('image',['class'=>'form-control input-lg', 'placeholder'=>'Imagem', 'accept'=>'.png,.jpg']) !!}
                    </div>
                    @if ($errors->has('image'))
                    <span class="help-block">
                        <strong>{{ $errors->first('image') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group col-sm-6" style="text-align: center">
                    <div class="col-sm-10">
                        <img src="{{ asset($creative->image) }}" height="128" width="128" class="img-rounded">
                    </div>
                </div>
            </div>
            <div class="form-group {{ $errors->has('category_id') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="lnr lnr-list"></i></span>
                    <select id='category_id'
                            name="category_id"
                            class="selectpicker form-control"
                            data-live-search="true"
                            title="Selecione uma Categoria"
                            data-actions-box="false"
                            data-select-all-text="Marcar todos"
                            data-deselect-all-text="Desmarcar todos">
                        @foreach($categories as $category)
                        <option
                            title="{{$category->name}}"
                            value="{{$category->id}}"
                            @if($creative->category != null && $category->id == $creative->category->id) selected @endif
                            {{ (collect(old('category_id'))->contains($category->id)) ? 'selected':'' }}>
                            {{$category->name}}
                    </option>
                    @endforeach
                </select>
            </div>
            @if ($errors->has('category'))
            <span class="help-block">
                <strong>{{ $errors->first('category_id') }}</strong>
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
