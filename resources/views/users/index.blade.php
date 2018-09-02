@extends('layouts/template')
@section('title', 'Usuários')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="">Lista de Usuários</a></li>
</ul>
<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-lock"></i>Lista de <b>Usuários</b>
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
                        <th class="text-center">E-MAIL</th>
                        <th class="text-center">NIVEL</th>
                        <th class="text-center">SALDO</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-center">ADD SALDO</th>
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
                url: '{!! route("users.data") !!}',
                type: 'GET',
                'beforeSend': function (request) {
                    request.setRequestHeader("token", $('meta[name="csrf-token"]').attr('content'));
                },
                data: function(data) {
                    for (var i = 0, len = data.columns.length; i < len; i++) {
                        if (! data.columns[i].search.value) delete data.columns[i].search;
                        if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                        if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                        if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                    }
                    delete data.search.regex;
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'roles', name: 'roles'},
                {data: 'revenue', name: 'revenue'},
                {data: 'status', name: 'status'},
                {data: 'credits', name: 'credits', orderable: false, searchable: false},
                {data: 'edit', name: 'edit', orderable: false, searchable: false},
                {data: 'show', name: 'show', orderable: false, searchable: false},
                {data: 'delete', name: 'delete', orderable: false, searchable: false},
            ],
            
        });
        $('.dataTables_filter input').attr('placeholder', 'Buscar');
    });
</script>
@endsection
