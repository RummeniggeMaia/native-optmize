@extends('layouts/template')
@section('title', 'Campanhas')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="">Lista de Campanhas Inativas</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-bullhorn"></i>Lista de <b>Campanhas Inativas</b>
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
                        <th class="text-center">MARCA</th>
                        <th class="text-center">TIPO</th>
                        <th class="text-center">LAYOUT</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-center">USUÁRIO</th>
                        <th class="text-center">EXIBIR</th>
                        <th class="text-center">ATIVAR</th>
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
                url: '{!! route("campaingns.inativesdata") !!}',
                type: 'GET',
                'beforeSend': function (request) {
                    request.setRequestHeader("token", $('meta[name="csrf-token"]').attr('content'));
                }
            },
            columns: [
                {data: 'brand', name: 'brand'},
                {data: 'name', name: 'name'},
                {data: 'type', name: 'type'},
                {data: 'type_layout', name: 'type_layout'},
                {data: 'status', name: 'status'},
                {data: 'users.name', name: 'users.name'},
                {data: 'show', name: 'show', orderable: false, searchable: false},
                {data: 'activate', name: 'activate', orderable: false, searchable: false},
            ],

        });
        $('.dataTables_filter input').attr('placeholder', 'Buscar');
    });
</script>
@endsection
