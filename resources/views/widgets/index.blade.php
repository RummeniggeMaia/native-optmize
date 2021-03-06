@extends('layouts/template')
@section('title', 'Widgets')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="">Lista de Widgets</a></li>
</ul>
<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-power-switch"></i>Lista de <b>Widgets</b>
            </h1>
        </div>
    </div>
</div>
<div class="row"> 
    <div class="col-md-12">             
        <div class="table-responsive">
            <table id="datatable" class="table table-vcenter table-borderbottom table-condensed">
                <thead>
                    <tr class="block-title">
                        <th class="text-center">NOME</th>
                        <th class="text-center">URL</th>
                        <th class="text-center">QUANTIDADE</th>
                        <th class="text-center">LAYOUT</th>
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
                url: '{!! route("widgets.data") !!}',
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
                {data: 'url', name: 'url'},
                {data: 'quantity', name: 'quantity'},
                {data: 'type_layout', name: 'type_layout'},
                {data: 'edit', name: 'edit', orderable: false, searchable: false},
                {data: 'show', name: 'show', orderable: false, searchable: false},
                {data: 'delete', name: 'delete', orderable: false, searchable: false},
            ],

        });
        $('.dataTables_filter input').attr('placeholder', 'Buscar');
    });
</script>
@endsection
