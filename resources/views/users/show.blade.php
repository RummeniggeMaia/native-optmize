@extends('layouts/template')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="">Exibir Usuários</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-power-switch"></i>Dados do <b>Usuário</b><br><small>Este é seu painel, cuide bem dele :)</small>
            </h1>
        </div>
    </div>
</div>

<form class="form-horizontal">
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Nome</label>
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
        <label for="skype" class="col-sm-2 control-label">Skype</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="skype" placeholder="{{ $user->skype }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <a href="{{ route('users.index')}}" class="btn btn-primary">Voltar</a>
        </div>
    </div>
</form>
@stop
