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
                        <div class="block">
                            <div class="block-title">
                                <h2>Estatísticas diárias deste mês</h2>
                            </div>
                            <canvas id="widgetDaily"></canvas>
                        </div>
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
<script src="{{ asset('js/Chart.min.js') }}"></script>
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
        $.ajax({
            dataType: "json",
            accepts: "application/json",
            method: 'GET',
            url: '{!! route("widgets.dailycl", ["id" => $widget->id]) !!}'
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
            let myChart = new Chart($('#widgetDaily'), {
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
