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
                <i class="lnr lnr-list"></i>Lista de <b>Categorias</b>
            </h1>
        </div>
    </div>
</div>
<div class="row"> 
    <div class="col-md-12">             
        <div class="table-responsive">
            <table id="datatable" class="table table-vcenter table-borderbottom table-condensed">
                <thead>
                    <tr class="bg-info">
                        <th class="text-center">NOME</th>
                        <th class="text-center">EDITAR</th>
                        <th class="text-center">EXIBIR</th>
                        <th class="text-center">EXCLUIR</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        App.datatables();
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{!! route("categories.data") !!}',
                type: 'GET',
                'beforeSend': function (request) {
                    request.setRequestHeader("token", $('meta[name="csrf-token"]').attr('content'));
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'edit', name: 'edit', orderable: false, searchable: false},
                {data: 'show', name: 'show', orderable: false, searchable: false},
                {data: 'delete', name: 'delete', orderable: false, searchable: false},
            ],

        });
        $('.dataTables_filter input').attr('placeholder', 'Buscar');
    });
</script>
@endsection
