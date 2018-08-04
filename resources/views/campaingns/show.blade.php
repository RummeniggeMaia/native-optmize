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
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input title="Campanha expira em:" type="text" class="form-control input-lg" id="expires" placeholder="{{ date('d-m-Y', strtotime($campaingn->expires_in)) }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-pause"></i></span>
                        <input title="Campanha pausada:" type="text" class="form-control input-lg" id="expires" placeholder="{{ $campaingn->paused ? 'Sim' : 'Não' }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="block">
                            <div class="block-title">
                                <h2>Estatísticas diárias deste mês</h2>
                            </div>
                            <canvas id="campaignDaily"></canvas>
                        </div>
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
<script src="{{ asset('js/Chart.min.js') }}"></script>
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
                {data: 'image', name: 'image'},
                {data: 'brand', name: 'brand'},
                {data: 'name', name: 'name'},
                {data: 'url', name: 'url'},
            ],
        });
        $('.dataTables_filter input').attr('placeholder', 'Buscar');
        $.ajax({
            dataType: "json",
            accepts: "application/json",
            method: 'GET',
            url: '{!! route("campaingns.dailycl", ["id" => $campaingn->id]) !!}'
        }).done(function (data) {
            let clicks = new Array();
            let revenues = new Array(); 
            let impressions = new Array();
            let labels = new Array();
            $.each(data, function (i, v) {
                clicks.push(v['clicks']);
                revenues.push(v['revenues']);
                impressions.push(v['impressions']);
                labels.push(v['day']);
            });
            let lines = {
                'labels' : labels,
                'datasets' : [{
                    label: 'Clicks',
                    fill: false,
                    backgroundColor: "rgb(54,128,45)",
                    borderColor: "rgb(54,128,45)", // The main line color
                    borderCapStyle: 'square',
                    borderDash: [], // try [5, 15] for instance
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "black",
                    pointBackgroundColor: "white",
                    pointBorderWidth: 1,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: "#17c4bb",
                    pointHoverBorderColor: "blue",
                    pointHoverBorderWidth: 2,
                    pointRadius: 4,
                    pointHitRadius: 10,
                    data: clicks,
                }, {
                    label: 'Impressions',
                    fill: false,
                    backgroundColor: "#A8DCA8",
                    borderColor: "#A8DCA8", // The main line color
                    borderCapStyle: 'square',
                    borderDash: [], // try [5, 15] for instance
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "black",
                    pointBackgroundColor: "white",
                    pointBorderWidth: 1,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: "#17c4bb",
                    pointHoverBorderColor: "blue",
                    pointHoverBorderWidth: 2,
                    pointRadius: 4,
                    pointHitRadius: 10,
                    data: impressions,
                }, {
                    label: 'Revenues R$',
                    fill: false,
                    backgroundColor: "#979797",
                    borderColor: "#979797", // The main line color
                    borderCapStyle: 'square',
                    borderDash: [], // try [5, 15] for instance
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "black",
                    pointBackgroundColor: "white",
                    pointBorderWidth: 1,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: "#17c4bb",
                    pointHoverBorderColor: "blue",
                    pointHoverBorderWidth: 2,
                    pointRadius: 4,
                    pointHitRadius: 10,
                    data: revenues,
                }]
            };
            let myChart = new Chart($('#campaignDaily'), {
                type: 'line',
                data: lines,
                options: {
                    elements: {
                        line: {
                            tension: 0, 
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            });
        }).fail(function () {
            console.log('Gráfico diário dos widgets não pode ser carregado.');
        });
    });
</script>
@stop
