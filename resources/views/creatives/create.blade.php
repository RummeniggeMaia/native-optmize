@extends('layouts.template')

@section('content')

<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="">Adicionar Anúncio</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-power-switch"></i>Adicionar <b>Anúncio</b><br><small>Este é seu painel, cuide bem dele :)</small>
            </h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="block">

            {!! Form::open(['url' => 'creatives', 'files' => true]) !!}
            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                {!! Form::label('Name', 'Nome:') !!}
                {!! Form::text('name',null,['id'=>'name', 'class'=>'form-control']) !!}
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
            <div class="form-group {{ $errors->has('url') ? ' has-error' : '' }}">
                {!! Form::label('URL', 'URL:') !!}
                {!! Form::text('url',null,['id'=>'url', 'class'=>'form-control']) !!}
                @if ($errors->has('url'))
                <span class="help-block">
                    <strong>{{ $errors->first('url') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                {!! Form::label('Image', 'Imagem:') !!}
                {!! Form::file('image',['class'=>'form-control', 'accept'=>'.png,.jpg']) !!}
                @if ($errors->has('image'))
                <span class="help-block">
                    <strong>{{ $errors->first('image') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('category') ? ' has-error' : '' }}">
                {!! Form::label('Category', 'Categoria:') !!}
                <select id='category'
                        name="category_id"
                        class="selectpicker form-control"
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
            <div class="form-group">
                {!! Form::submit('Salvar', ['class' => 'btn btn-primary form-control']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop
