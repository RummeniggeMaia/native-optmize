@extends('layouts/template')
@section('title', 'Campanhas')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="{{ route('campaingns') }}">Lista de Campanhas</a></li>
    <li><a href="">Exibir Campanhas</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-bullhorn"></i>Exibir <b>Campanha</b>
            </h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="block">
            <form class="form-bordered">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input title="Nome" type="text" class="form-control  input-lg" id="name" placeholder="{{ $campaingn->name }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tags"></i></span>
                        <input title="Marca" type="text" class="form-control  input-lg" id="brand" placeholder="{{ $campaingn->brand }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-star"></i></span>
                        <input title="Tipo da campanha" type="text" class="form-control input-lg" id="type" placeholder="{{ $campaingn->type }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                        <input title="Custo por click" type="text" class="form-control input-lg" id="cpc" placeholder="{{ $campaingn->cpc }}" readonly>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="datatable" class="table table-vcenter table-borderbottom table-condensed">
                        <thead>
                            <tr class="bg-info">
                                <th class="text-center">IMAGEM</th>
                                <th class="text-center">MARCA</th>
                                <th class="text-center">NOME</th>
                                <th class="text-center">URL</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </form>
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
                url: '{!!  route("campaingns.creatives", $campaingn->id) !!}',
                type: 'GET',
                'beforeSend': function (request) {
                    request.setRequestHeader("token", $('meta[name="csrf-token"]').attr('content'));
                }
            },
            columns: [
                {data: 'image', name: 'image'},
                {data: 'brand', name: 'brand'},
                {data: 'name', name: 'name'},
                {data: 'url', name: 'url'},
            ],
        });
        $('.dataTables_filter input').attr('placeholder', 'Buscar');
    });
</script>
@stop
