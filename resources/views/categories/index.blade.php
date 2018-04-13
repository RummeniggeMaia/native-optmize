@extends('layouts/template')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="">Lista de Categorias</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-power-switch"></i>Lista de <b>Categorias</b><br><small>Este é seu painel, cuide bem dele :)</small>
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
            <th colspan="3">Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
            <td><a href="{{route('categories.show', $category->id)}}" class="btn btn-primary">Mostrar</a></td>
            <td><a href="{{route('categories.edit', $category->id)}}" class="btn btn-warning">Atualizar</a></td>
            <td>
                {!! Form::open(['method' => 'DELETE', 'route'=>['categories.destroy', $category->id]]) !!}
                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                {!! Form::close() !!}
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot class="bg-info">
        <tr>
            <td colspan="7" style="text-align: right;font-weight: bold">
                Exibindo de {{ ($categories->currentPage() - 1) * 5 + 1 }}
                a @if(($categories->currentPage() - 1) * 5 + 5 > $categories->total())
                    {{ $categories->total() }}
                  @else
                    {{ ($categories->currentPage() - 1) * 5 + 5 }}
                  @endif
                de {{ $categories->total() }} categories
            </td>
        </tr>
    </tfoot>
</table>
{{ $categories->links() }}
@endsection
