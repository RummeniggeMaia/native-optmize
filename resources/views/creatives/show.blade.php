@extends('layouts/template')

@section('content')
<h1>Dados do Creative</h1>

<form class="form-horizontal">
    <div class="form-group">
        <label for="image" class="col-sm-2 control-label">Imagem</label>
        <div class="col-sm-10">
            <img src="{{ asset($creative->image) }}" height="180" width="180" class="img-rounded">
        </div>
    </div>
    <div class="form-group">
        <label for="isbn" class="col-sm-2 control-label">Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="isbn" placeholder="{{ $creative->name }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">URL</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="title" placeholder="{{ $creative->url }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">Category</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="title" placeholder="{{ $category->name }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <a href="{{ route('creatives')}}" class="btn btn-primary">Voltar</a>
        </div>
    </div>
</form>
@stop
