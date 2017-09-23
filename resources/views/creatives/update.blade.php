@extends('layout.template')
@section('content')
<h1>Atualizar Creative</h1>
{!! Form::model($creative,['method' => 'update','route'=>['creatives.update',$creative->id]]) !!}
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
    {!! Form::submit('Atualizar', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@stop