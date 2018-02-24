@extends('layouts/template')
@section('content')
<h1>Dados da Campaingn</h1>

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
