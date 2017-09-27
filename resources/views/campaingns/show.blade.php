@extends('layout/template')
@section('content')
<h1>Dados da Campaingn</h1>

<form class="form-horizontal">
    <div class="form-group">
        <label for="isbn" class="col-sm-2 control-label">Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="isbn" placeholder="{{ $campaingn->name }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">Brand</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="title" placeholder="{{ $campaingn->brand }}" readonly>
        </div>
    </div>

    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">Creatives</label>
        <div class="col-sm-10">
            @foreach ($campaingn->creatives as $creative)
                <input type="text" class="form-control" id="title" placeholder="{{ $creative->name }}" readonly>
            @endforeach
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">Widgets</label>
        <div class="col-sm-10">
            @foreach ($campaingn->widgets as $widget)
                <input type="text" class="form-control" id="title" placeholder="{{ $widget->name }}" readonly>
            @endforeach
        </div>
    </div>
    <di1v class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <a href="{{ route('campaingns.index')}}" class="btn btn-primary">Voltar</a>
        </div>
        </div>
</form>
@stop