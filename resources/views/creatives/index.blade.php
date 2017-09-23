@extends('layout/template')

@section('content')
<h1>Creatives</h1>
<a href="{{route('creatives.create')}}" class="btn btn-success">Novo Creative</a>
<hr>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr class="bg-info">
            <th>Id</th>
            <th>Name</th>
            <th>Url</th>
            <th>Image</th>
            <th colspan="3">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($creatives as $creative)
        <tr>
            <td>{{ $creative->id }}</td>
            <td>{{ $creative->name }}</td>
            <td>{{ $creative->url }}</td>
            <td><img src="{{asset('img/'.$creative->image)}}" height="35" width="70"></td>
            <td><a href="{{route('creatives.show', $creative->id)}}" class="btn btn-primary">Mostrar</a></td>
            <td><a href="{{route('creatives.edit', $creative->id)}}" class="btn btn-warning">Atualizar</a></td>
            <td>
                {!! Form::open(['method' => 'DELETE', 'route'=>['creatives.destroy', $creative->id]]) !!}
                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
            </td>
        </tr>
        @endforeach
    </tbody>

</table>
@endsection
