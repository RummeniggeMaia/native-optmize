@extends('layouts/template')
@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="">Exibir Campanhas</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-power-switch"></i>Dados da <b>Campanha</b><br><small>Este é seu painel, cuide bem dele :)</small>
            </h1>
        </div>
    </div>
</div>


<form class="form-horizontal">
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Nome</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="name" placeholder="{{ $campaingn->name }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label for="brand" class="col-sm-2 control-label">Marca</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="brand" placeholder="{{ $campaingn->brand }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label for="creatives" class="col-sm-2 control-label">Creatives</label>
        <div  class="col-sm-10">
            @foreach($campaingn->creatives as $creative)
                <input id="creatives" type="text" class="form-control"
                    placeholder="{{ $creative->name }}" style="margin-bottom: 5px" readonly>
            @endforeach
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <a href="{{ route('campaingns')}}" class="btn btn-primary">Voltar</a>
        </div>
    </div>
</form>
@stop
