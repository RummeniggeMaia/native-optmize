@extends('layouts.template')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="{{ route('categories') }}">Lista de Categorias</a></li>
    <li><a href="">Editar Categoria</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-power-switch"></i>Editar <b>Categoria</b>
            </h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="block">
            {!! Form::model($category,['method' => 'patch','route'=>['categories.update',$category->id]]) !!}
            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="gi gi-user"></i></span>
                    {!! Form::text('name',null,['id'=>'name', 'class'=>'form-control input-lg', 'placeholder'=>'Nome', 'required']) !!}
                </div>
                @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group form-actions text-center">
                {!! Form::submit('Atualizar', ['class' => 'btn btn-md btn-default']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop
