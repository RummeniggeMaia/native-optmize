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
<div class="form-group">
    {!! Form::label('Image', 'Image:') !!}
    {!! Form::text('image',null,['class'=>'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('Category', 'Category:') !!}
    <select class="form-control" name="related_category" id="related_category">
        @foreach($categories as $category)
        <option @If($creative->related_category === $category->id)selected @endif value="{{$category->id}}">{{$category->name}}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    {!! Form::submit('Atualizar', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@stop