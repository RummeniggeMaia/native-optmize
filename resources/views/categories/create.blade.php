@extends('layouts.template')

@section('content')
<h1>Criar Categoria</h1>
{!! Form::open(['url' => 'categories']) !!}
<div class="form-group">
    {!! Form::label('Name', 'Name:') !!}
    {!! Form::text('name',null,['class'=>'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::submit('Salvar', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@stop
