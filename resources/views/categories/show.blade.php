@extends('layouts/template')

@section('content')
<h1>Dados da Categoria</h1>

<form class="form-horizontal">
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="name" placeholder="{{ $category->name }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <a href="{{ route('categories.index')}}" class="btn btn-primary">Voltar</a>
        </div>
    </div>
</form>
@stop
