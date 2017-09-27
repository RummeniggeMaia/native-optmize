@extends('layout/template')
@section('content')
<h1>Dados do Widget</h1>

<form class="form-horizontal">
    <div class="form-group">
        <label for="isbn" class="col-sm-2 control-label">Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="isbn" placeholder="{{ $widget->name }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">URL</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="title" placeholder="{{ $widget->url }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <a href="{{ route('widgets.index')}}" class="btn btn-primary">Voltar</a>
        </div>
    </div>
</form>
@stop