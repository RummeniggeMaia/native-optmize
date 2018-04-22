@extends('layouts/template')
@section('title', 'Campanhas')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="{{ route('campaingns') }}">Lista de Campanhas</a></li>
    <li><a href="">Exibir Campanhas</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-bullhorn"></i>Exibir <b>Campanha</b>
            </h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="block">
            <form class="form-bordered">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input title="Nome" type="text" class="form-control  input-lg" id="name" placeholder="{{ $campaingn->name }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tags"></i></span>
                        <input title="Marca" type="text" class="form-control  input-lg" id="brand" placeholder="{{ $campaingn->brand }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-star"></i></span>
                        <input title="Tipo da campanha" type="text" class="form-control input-lg" id="brand" placeholder="{{ $campaingn->type }}" readonly>
                    </div>
                </div>
                <div class="form-group input-lg">
                    <div class="input-group" title="Anúncios">
                        <span class="input-group-addon"><i class="fa fa-bullhorn"></i></span>
                        @foreach($campaingn->creatives as $creative)
                        <input id="creatives" type="text" class="form-control"
                               placeholder="{{ $creative->name }}" style="margin-bottom: 5px" readonly>
                        @endforeach
                    </div>
                </div>
                <div class="form-group form-actions text-center">
                    <a href="{{ route('campaingns')}}" class="btn btn-md btn-default">Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div> 
@stop
