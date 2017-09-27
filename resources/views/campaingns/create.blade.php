@extends('layout.template')
@section('content')
<h1>Criar Campaingn</h1>
{!! Form::open(['url' => 'api/campaingns']) !!}
<div class="form-group">
    {!! Form::label('Name', 'Name:') !!}
    {!! Form::text('name',null,['class'=>'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('Brand', 'Brand:') !!}
    {!! Form::text('brand',null,['class'=>'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('Creative', 'Creative:') !!}
    <select class="form-control" name="target_creative" id="target_creative">
        @foreach($creatives as $creative)
        <option value="{{$creative->id}}">{{$creative->name}}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    {!! Form::label('Widget', 'Widget:') !!}
    <select class="form-control" name="related_widget" id="related_widget">
        @foreach($widgets as $widget)
        <option value="{{$widget->id}}">{{$widget->name}}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    {!! Form::submit('Salvar', ['class' => 'btn btn-primary form-control']) !!}
</div>
{!! Form::close() !!}
@stop