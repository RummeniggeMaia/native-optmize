@extends('layouts/template')

@section('content')
<h1>Widgets</h1>
<a href="{{route('widgets.create')}}" class="btn btn-success">Novo Widget</a>
<hr>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr class="bg-info">
            <th>Id</th>
            <th>Name</th>
            <th>Url</th>
            <th colspan="3">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($widgets as $widget)
        <tr>
            <td>{{ $widget->id }}</td>
            <td>{{ $widget->name }}</td>
            <td>{{ $widget->url }}</td>
            <td><a href="{{route('widgets.show', $widget->id)}}" class="btn btn-primary">Mostrar</a></td>
            <td><a href="{{route('widgets.edit', $widget->id)}}" class="btn btn-warning">Atualizar</a></td>
            <td>
                {!! Form::open(['method' => 'DELETE', 'route'=>['widgets.destroy', $widget->id]]) !!}
                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
            </td>
        </tr>
        @endforeach
    </tbody>

</table>
@endsection
