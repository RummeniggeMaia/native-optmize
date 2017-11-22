@extends('layouts.template')

@section('content')
<h1>Criar Categoria</h1>
{!! Form::open(['url' => 'categories']) !!}
<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    {!! Form::label('Name', 'Name:') !!}
    {!! Form::text('name',null,['id'=>'name', 'class'=>'form-control']) !!}
    @if ($errors->has('name'))
        <span class="help-block">
            <strong>{{ $errors->first('name') }}</strong>
         </span>
    @endif
</div>
<div class="form-group">
    {!! Form::submit('Salvar', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@stop
