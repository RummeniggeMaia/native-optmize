@extends('layouts.template')
@section('title', 'Widgets')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="{{ route('widgets') }}">Lista de Widgets</a></li>
    <li><a href="">Editar Widget</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-power-switch"></i>Editar <b>Widget</b>
            </h1>
        </div>
    </div>
</div>

@stop
