@extends('layouts/template')
@section('content')
<h1>Dados do Widget</h1>

<form class="form-horizontal">
    <div class="form-group">
        <label for="urlname" class="col-sm-2 control-label">Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="name" placeholder="{{ $widget->name }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label for="url" class="col-sm-2 control-label">URL</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="url" placeholder="{{ $widget->url }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label for="type" class="col-sm-2 control-label">Type</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="type"
                    placeholder="{{ [
                        '0' => '----------------------',
                        '1' => 'Barra Lateral Direita',
                        '2' => 'Barra Lateral Esquerda',
                        '3' => 'Central'
                    ][$widget->type] }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Campaingns</label>
        <div class="col-sm-10">
            @foreach($widget->campaingns as $campaingn)
                <input type="text" class="form-control" style="margin-bottom: 5px"
                    placeholder="{{ $campaingn->name }}" readonly>
            @endforeach
        </div>
    </div>
    <div class="form-group">
        <label for="isbn" class="col-sm-2 control-label">Código:</label>
        <div class="col-sm-10">
            {{ Form::textarea('jscode', $jsCode, ['class'=>'form-control']) }}
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <a href="{{ route('widgets')}}" class="btn btn-primary">Voltar</a>
        </div>
    </div>
</form>
@stop
