@extends('layouts.template')

@section('content')

<h1>Criar Creative</h1>
{!! Form::open(['url' => 'creatives', 'files' => true]) !!}
<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    {!! Form::label('Title', 'Title:') !!}
    {!! Form::text('name',null,['id'=>'name', 'class'=>'form-control']) !!}
    @if ($errors->has('name'))
    <span class="help-block">
        <strong>{{ $errors->first('name') }}</strong>
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
    {!! Form::label('Image', 'Image:') !!}
    {!! Form::file('image',['class'=>'form-control', 'accept'=>'.png,.jpg']) !!}
    @if ($errors->has('image'))
    <span class="help-block">
        <strong>{{ $errors->first('image') }}</strong>
    </span>
    @endif
</div>
<div class="form-group {{ $errors->has('related_category') ? ' has-error' : '' }}">
    {!! Form::label('Category', 'Category:') !!}
    <select id='related_category'
            name="related_category"
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
            {{ (collect(old('related_category'))->contains($category->id)) ? 'selected':'' }}>
            {{$category->name}}
        </option>
        @endforeach
    </select>
    @if ($errors->has('related_category'))
    <span class="help-block">
        <strong>{{ $errors->first('related_category') }}</strong>
    </span>
    @endif
</div>
<div class="form-group">
    {!! Form::submit('Salvar', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@stop
