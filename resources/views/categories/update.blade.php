@extends('layouts.template')

@section('content')
<h1>Atualizar Categoria</h1>
{!! Form::model($category,['method' => 'patch','route'=>['categories.update',$category->id]]) !!}
<div class="form-group">
    {!! Form::label('Name', 'Name:') !!}
    {!! Form::text('name',null,['class'=>'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::submit('Atualizar', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@stop
