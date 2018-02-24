@extends('layouts.template')

@section('content')

<h1>Atualizar Creative</h1>
{!! Form::model($creative,['method' => 'patch','route'=>['creatives.update',$creative->id], 'files' => true]) !!}
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
<div class="form-group {{ $errors->has('url') ? ' has-error' : '' }}">
    {!! Form::label('URL', 'URL:') !!}
    {!! Form::text('url',null,['class'=>'form-control']) !!}
    @if ($errors->has('url'))
    <span class="help-block">
        <strong>{{ $errors->first('url') }}</strong>
    </span>
    @endif
</div>
<div class="row">
    <div class="form-group col-sm-6 {{ $errors->has('image') ? ' has-error' : '' }}">
        {!! Form::label('Image', 'Imagem:') !!}
        {!! Form::file('image',['class'=>'form-control', 'accept'=>'.png,.jpg']) !!}
        @if ($errors->has('image'))
        <span class="help-block">
            <strong>{{ $errors->first('image') }}</strong>
        </span>
        @endif
    </div>
    <div class="form-group col-sm-6" style="text-align: center">
        <div class="col-sm-10">
            <img src="{{ asset($creative->image) }}" height="180" width="180" class="img-rounded">
        </div>
    </div>
</div>
<div class="form-group {{ $errors->has('category_id') ? ' has-error' : '' }}">
    {!! Form::label('Category', 'Categoria:') !!}
    <select id='category_id'
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
            @if($category->id == $creative->category->id) selected @endif
            {{ (collect(old('category_id'))->contains($category->id)) ? 'selected':'' }}>
            {{$category->name}}
        </option>
        @endforeach
    </select>
    @if ($errors->has('category'))
    <span class="help-block">
        <strong>{{ $errors->first('category_id') }}</strong>
    </span>
    @endif
</div>
<div class="form-group">
    {!! Form::submit('Atualizar', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@stop
