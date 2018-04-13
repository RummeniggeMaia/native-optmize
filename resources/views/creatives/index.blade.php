@extends('layouts/template')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="">Lista de Anúncios</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-power-switch"></i>Lista de <b>Anúncios</b><br><small>Este é seu painel, cuide bem dele :)</small>
            </h1>
        </div>
    </div>
</div>

<hr>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr class="bg-info">
                <th>Id</th>
                <th>Imagem</th>
                <th>Marca</th>
                <th>Nome</th>
                <th>URL</th>
                <th colspan="3">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($creatives as $creative)
            <tr>
                <td>{{ $creative->id }}</td>
                <td><img src="{{ asset($creative->image) }}" height="154" width="128"></td>
                <td>{{ $creative->brand }}</td>
                <td>{{ $creative->name }}</td>
                <td>{{ $creative->url }}</td>
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
        <tfoot class="bg-info">
            <tr>
                <td colspan="8" style="text-align: right;font-weight: bold">
                    Exibindo de {{ ($creatives->currentPage() - 1) * 5 + 1 }}
                    a @if(($creatives->currentPage() - 1) * 5 + 5 > $creatives->total())
                    {{ $creatives->total() }}
                    @else
                    {{ ($creatives->currentPage() - 1) * 5 + 5 }}
                    @endif
                    de {{ $creatives->total() }} creatives
                </td>
            </tr>
        </tfoot>
    </table>
</div>
{{ $creatives->links() }}
@endsection
