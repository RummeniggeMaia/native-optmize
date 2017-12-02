@extends('layouts/template')

@section('content')
<h1>Dados do User</h1>

<form class="form-horizontal">
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="name" placeholder="{{ $user->name }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="col-sm-2 control-label">E-mail</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="email" placeholder="{{ $user->email }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <a href="{{ route('users.index')}}" class="btn btn-primary">Voltar</a>
        </div>
    </div>
</form>
@stop
