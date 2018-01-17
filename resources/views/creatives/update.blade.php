@extends('layouts.template')

@section('content')

<h1>Atualizar Creative</h1>
{!! Form::model($creative,['method' => 'patch','route'=>['creatives.update',$creative->id]]) !!}
<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    {!! Form::label('Name', 'Name:') !!}
    {!! Form::text('name',null,['class'=>'form-control']) !!}
    @if ($errors->has('name'))
    <span class="help-block">
        <strong>{{ $errors->first('name') }}</strong>
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
<div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
    {!! Form::label('Image', 'Image:') !!}
    {!! Form::file('image',['class'=>'form-control', 'accept'=>'.png,.jpg']) !!}
    @if ($errors->has('image'))
    <span class="help-block">
        <strong>{{ $errors->first('image') }}</strong>
    </span>
    @endif
</div>
<div class="form-group">
    <label for="image" class="col-sm-2 control-label">Imagem</label>
    <div class="col-sm-10">
        <img src="{{ asset($creative->image) }}" height="180" width="180" class="img-rounded">
    </div>
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
        <optgroup label="Categories Fixas">
            @foreach($categories as $category)
            <option
                title="{{$category->name}}"
                value="{{$category->id}}"
                @if($categories->contains($creative->related_category)) selected @endif
                {{ (collect(old('related_category'))->contains($category->id)) ? 'selected':'' }}>
                {{$category->name}}
            </option>
            @endforeach
        </optgroup>
        <optgroup label="Minhas Categories">
            @foreach($myCategories as $category)
            <option
                title="{{$category->name}}"
                value="{{$category->id}}"
                @if($myCategories->contains($creative->related_category)) selected @endif
                {{ (collect(old('related_category'))->contains($category->id)) ? 'selected':'' }}>
                {{$category->name}}
            </option>
            @endforeach
        </optgroup>
    </select>
    @if ($errors->has('related_category'))
    <span class="help-block">
        <strong>{{ $errors->first('related_category') }}</strong>
    </span>
    @endif
</div>
<div class="form-group">
    {!! Form::submit('Atualizar', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@stop
