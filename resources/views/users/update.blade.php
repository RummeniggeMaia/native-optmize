@extends('layouts.template')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="">Editar Usuário</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-power-switch"></i>Editar <b>Usuário</b><br><small>Este é seu painel, cuide bem dele :)</small>
            </h1>
        </div>
    </div>
</div>
{!! Form::model($user,['method' => 'patch','route'=>['users.update',$user->id]]) !!}
<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
    {!! Form::label('Name', 'Nome:') !!}
    {!! Form::text('name',null,['id'=>'name', 'class'=>'form-control']) !!}
    @if ($errors->has('name'))
    <span class="help-block">
        <strong>{{ $errors->first('name') }}</strong>
    </span>
    @endif
</div>
<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
    {!! Form::label('E-mail', 'E-mail:') !!}
    {!! Form::text('email',null,['id'=>'email', 'class'=>'form-control']) !!}
    @if ($errors->has('email'))
    <span class="help-block">
        <strong>{{ $errors->first('email') }}</strong>
    </span>
    @endif
</div>
<div class="form-group {{ $errors->has('skype') ? ' has-error' : '' }}">
    {!! Form::label('Skype', 'Skype:') !!}
    {!! Form::text('skype',null,['id'=>'skype', 'class'=>'form-control']) !!}
    @if ($errors->has('skype'))
    <span class="help-block">
        <strong>{{ $errors->first('skype') }}</strong>
    </span>
    @endif
</div>
<div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
    {!! Form::label('Password', 'Senha:') !!}
    {!! Form::password('password',['id'=>'password', 'class'=>'form-control']) !!}
    @if ($errors->has('password'))
    <span class="help-block">
        <strong>{{ $errors->first('password') }}</strong>
    </span>
    @endif
</div>
<div class="form-group">
    {!! Form::submit('Atualizar', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@stop
