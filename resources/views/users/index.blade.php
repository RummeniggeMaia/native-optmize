@extends('layouts/template')

@section('content')
<h1>Users</h1>
<a href="{{route('users.create')}}" class="btn btn-success">Novo User</a>
<hr>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr class="bg-info">
            <th>Id</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Skype</th>
            <th colspan="3">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->skype }}</td>
            <td><a href="{{route('users.show', $user->id)}}" class="btn btn-primary">Mostrar</a></td>
            <td><a href="{{route('users.edit', $user->id)}}" class="btn btn-warning">Atualizar</a></td>
            <td>
                {!! Form::open(['method' => 'DELETE', 'route'=>['users.destroy', $user->id]]) !!}
                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot class="bg-info">
        <tr>
            <td colspan="7" style="text-align: right;font-weight: bold">
                Exibindo de {{ ($users->currentPage() - 1) * 5 + 1 }}
                a @if(($users->currentPage() - 1) * 5 + 5 > $users->total())
                    {{ $users->total() }}
                  @else
                    {{ ($users->currentPage() - 1) * 5 + 5 }}
                  @endif
                de {{ $users->total() }} users
            </td>
        </tr>
    </tfoot>
</table>
{{ $users->links() }}
@endsection
