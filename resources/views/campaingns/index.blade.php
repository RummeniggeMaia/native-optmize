@extends('layouts/template')

@section('content')
<h1>Campaings</h1>
<a href="{{route('campaingns.create')}}" class="btn btn-success">Nova Campaingn</a>
<hr>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr class="bg-info">
            <th>Id</th>
            <th>Name</th>
            <th>Brand</th>
            <th colspan="3">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($campaingns as $campaingn)
        <tr>
            <td>{{ $campaingn->id }}</td>
            <td>{{ $campaingn->name }}</td>
            <td>{{ $campaingn->brand }}</td>
            <td><a href="{{route('campaingns.show', $campaingn->id)}}" class="btn btn-primary">Mostrar</a></td>
            <td><a href="{{route('campaingns.edit', $campaingn->id)}}" class="btn btn-warning">Atualizar</a></td>
            <td>
                {!! Form::open(['method' => 'DELETE', 'route'=>['campaingns.destroy', $campaingn->id]]) !!}
                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
            </td>
        </tr>
        @endforeach
    </tbody>

</table>
@endsection
