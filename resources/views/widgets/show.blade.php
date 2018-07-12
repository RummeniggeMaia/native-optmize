@extends('layouts/template')
@section('title', 'Widgets')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="{{ route('widgets') }}">Lista de Widgets</a></li>
    <li><a href="#">Exibir Widgets</a></li>
</ul>
<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-power-switch"></i>Exibir <b>Widget</b>
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
                        <input type="text" class="form-control input-lg" id="name" placeholder="{{ $widget->name }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                        <input type="text" class="form-control input-lg" id="url" placeholder="{{ $widget->url }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-star"></i></span>
                        <input type="text" class="form-control" id="quantity"
                               placeholder="{{ $widget->quantity }}" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-code"></i></span>
                        {{ Form::textarea('code', $code, ['class'=>'form-control textarea', 'rows' => 3, 'title' => 'Código dos anúncios']) }}
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-code"></i></span>
                        {{ Form::textarea('iframe', $iframe, ['class'=>'form-control textarea', 'rows' => 3, 'title' => 'Código IFrame']) }}
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-md-12">             
                        <div class="table-responsive">
                            <table id="datatable" class="table table-vcenter table-borderbottom table-condensed">
                                <thead>
                                    <tr class="bg-info">
                                        <th>CREATIVE</th>
                                        <th>IMAGEM</th>
                                        <th>CLICKS</th>
                                        <th>VISUALIZAÇÕES</th>
                                        <th>REVENUES</th>
                                        <th>CAMPANHA</th>
                                        <th>TIPO</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
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
                url: '{!! route("widgets.logs", $widget->id) !!}',
                type: 'GET',
                'beforeSend': function (request) {
                    request.setRequestHeader("token", $('meta[name="csrf-token"]').attr('content'));
                }
            },
            columns: [
                {data: 'creative.name', name: 'creative.name'},
                {data: 'image', name: 'creative.image'},
                {data: 'clicks', name: 'clicks'},
                {data: 'impressions', name: 'impressions'},
                {data: 'revenue', name: 'revenue'},
                {data: 'campaingn.name', name: 'campaingn.name'},
                {data: 'campaingn.type', name: 'campaingn.type'},
            ],

        });
        $('.dataTables_filter input').attr('placeholder', 'Buscar');
    });
</script>
@stop
