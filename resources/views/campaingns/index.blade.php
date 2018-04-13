@extends('layouts/template')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="">Lista de Campanhas</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-power-switch"></i>Lista de <b>Campanhas</b><br><small>Este é seu painel, cuide bem dele :)</small>
            </h1>
        </div>
    </div>
</div>

<hr>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr class="bg-info">
            <th>Id</th>
            <th>Nome</th>
            <th>Marca</th>
            <th colspan="3">Ações</th>
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
    <tfoot class="bg-info">
        <tr>
            <td colspan="7" style="text-align: right;font-weight: bold">
                Exibindo de {{ ($campaingns->currentPage() - 1) * 5 + 1 }}
                a @if(($campaingns->currentPage() - 1) * 5 + 5 > $campaingns->total())
                    {{ $campaingns->total() }}
                  @else
                    {{ ($campaingns->currentPage() - 1) * 5 + 5 }}
                  @endif
                de {{ $campaingns->total() }} campaingns
            </td>
        </tr>
    </tfoot>
</table>
{{ $campaingns->links() }}
@endsection
