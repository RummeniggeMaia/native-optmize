@extends('layouts.template')
@section('title', 'Campanhas')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="">Pausar campanhas</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-pause"></i>Pausar <b>Campanhas</b>
            </h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="block">
            <div class="block-title">
                <h2>Deseja pausar todas as campanhas?</h2>
            </div>
            <div class="block-section">
                <div class="row">
                    {!! Form::open(['method' => 'GET', 'route'=>['campaingns.pauseall']]) !!}
                    <div class="col-md-6">
                        <a class="btn btn-xs btn-default btn-block" href="{{ route('home') }}">Não</a>
                    </div>
                    <div class="col-md-6">
                    {!! Form::button('SIM', ['type' => 'submit', 'class' => 'btn btn-xs btn-default btn-block']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
