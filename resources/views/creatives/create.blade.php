@extends('layout.template')
@section('content')
<h1>Criar Creative</h1>
{!! Form::open(['url' => 'api/creatives']) !!}
<div class="form-group">
    {!! Form::label('Name', 'Name:') !!}
    {!! Form::text('name',null,['class'=>'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('URL', 'URL:') !!}
    {!! Form::text('url',null,['class'=>'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('Image', 'Image:') !!}
    {!! Form::text('image',null,['class'=>'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('Category', 'Category:') !!}
    <select class="form-control" name="related_category" id="related_category">
        @foreach($categories as $category)
        <option value="{{$category->id}}">{{$category->name}}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    {!! Form::submit('Salvar', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@stop