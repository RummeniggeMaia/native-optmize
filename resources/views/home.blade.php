@extends('layouts.template') @section('title', 'Dashboard') @section('content') @auth
<ul class="breadcrumb breadcrumb-top">
    <li>
        <a href="{{ route('home') }}">Dashboard</a>
    </li>
</ul>
<!-- TODO Mudar versao do javascript -->
<script src="{{ asset('pago/js/pages/compCharts.js?v13') }}"></script>
<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                @If(Auth::user()->hasAnyRole(['admin', 'adver']))
                    <i class="fa fa-tv"></i>Estatísticas das
                    <b>Campanhas</b>
                @else
                    <i class="fa fa-tv"></i>Estatísticas dos
                    <b>Widgets</b>
                @endif
            </h1>
        </div>
    </div>
</div>
@include('comum.transactions')
<div class="row">
    <div class="col-sm-8">
        <div class="block">
            <div class="block-title">
                <h2>Geral</h2>
            </div>
            <div id="widgetsChartLine" class="chart" style="padding: 0px; position: relative;">
                <canvas class="flot-base" width="500" height="410" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 500px; height: 410px;"></canvas>
                <div class="flot-text" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; font-size: smaller; color: rgb(84, 84, 84);">
                    <div class="flot-x-axis flot-x1-axis xAxis x1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;">
                        <div class="flot-tick-label tickLabel" style="position: absolute; max-width: 41px; top: 395px; left: 22px; text-align: center;">Fev</div>
                        <div class="flot-tick-label tickLabel" style="position: absolute; max-width: 41px; top: 395px; left: 172px; text-align: center;">Mar</div>
                        <div class="flot-tick-label tickLabel" style="position: absolute; max-width: 41px; top: 395px; left: 325px; text-align: center;">Abr</div>
                        <div class="flot-tick-label tickLabel" style="position: absolute; max-width: 41px; top: 395px; left: 477px; text-align: center;">Mai</div>
                    </div>
                    <div class="flot-y-axis flot-y1-axis yAxis y1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;">
                        <div class="flot-tick-label tickLabel" style="position: absolute; top: 383px; left: 19px; text-align: right;">0</div>
                        <div class="flot-tick-label tickLabel" style="position: absolute; top: 257px; left: 6px; text-align: right;">500</div>
                        <div class="flot-tick-label tickLabel" style="position: absolute; top: 132px; left: 0px; text-align: right;">1000</div>
                        <div class="flot-tick-label tickLabel" style="position: absolute; top: 7px; left: 0px; text-align: right;">1500</div>
                    </div>
                </div>
                <canvas class="flot-overlay" width="500" height="410" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 500px; height: 410px;"></canvas>
                <div class="legend">
                    <div style="position: absolute; width: 112px; height: 56px; top: 24px; left: 45px; background-color: rgb(255, 255, 255); opacity: 0.85;">
                    </div>
                    <table style="position:absolute;top:24px;left:45px;;font-size:smaller;color:#545454">
                        <tbody>
                            <tr>
                                <td class="legendColorBox">
                                    <div style="border:1px solid #ccc;padding:1px">
                                        <div style="width:4px;height:0;border:5px solid rgb(52,152,219);overflow:hidden"></div>
                                    </div>
                                </td>
                                <td class="legendLabel"></td>
                            </tr>
                            <tr>
                                <td class="legendColorBox">
                                    <div style="border:1px solid #ccc;padding:1px">
                                        <div style="width:4px;height:0;border:5px solid rgb(51,51,51);overflow:hidden"></div>
                                    </div>
                                </td>
                                <td class="legendLabel"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="block">
            <div class="block-title">
                @If(Auth::user()->hasRole('publi'))
                    <h2>Lucros diários</h2>
                @elseif(Auth::user()->hasAnyRole(['admin', 'adver']))
                    <h2>Investimentos diários</h2>
                @endif
            </div>
            <ul class="list-group">
                <li class="list-group-item">
                    <span class="badge" style="background-color:#17c4bb">R$ {{ number_format($money['today'], 2) }}</span>
                    Hoje
                </li>
                <li class="list-group-item">
                    <span class="badge" style="background-color:#17c4bb">R$ {{ number_format($money['yesterday'], 2) }}</span>
                    Ontem
                </li>
                <li class="list-group-item">
                    <span class="badge" style="background-color:#17c4bb">R$ {{ number_format($money['thisWeek'], 2) }}</span>
                    Esta semana
                </li>
                <li class="list-group-item">
                    <span class="badge" style="background-color:#17c4bb">R$ {{ number_format($money['lastWeek'], 2) }}</span>
                    Semana passada
                </li>
                <li class="list-group-item">
                    <span class="badge" style="background-color:#17c4bb">R$ {{ number_format($money['thisMonth'], 2) }}</span>
                    Este mês
                </li>
                <li class="list-group-item">
                    <span class="badge" style="background-color:#17c4bb">R$ {{ number_format($money['lastMonth'], 2) }}</span>
                    Mês passado
                </li>
                <li class="list-group-item">
                        <span class="badge" style="background-color:#17c4bb">R$ {{ number_format($money['thisYear'], 2) }}</span>
                        Este ano
                </li>
                <li class="list-group-item">
                    <span class="badge" style="background-color:#17c4bb">R$ {{ number_format($money['lastYear'], 2) }}</span>
                    Ano passado
                </li>
            </ul>
        </div>
        @if(Auth::user()->hasAnyRole(['admin', 'adver']))
            <a href="{{ route('campaingns.pauseconfirm') }}" class="btn btn-xs btn-default btn-block"><i class="fa fa-pause"></i>&nbsp;&nbsp;&nbsp;&nbsp;PAUSAR CAMPANHAS</a>
        @endif
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $.ajax({
            dataType: "json",
            accepts: "application/json",
            method: 'GET',
            url: "{{ Auth::user()->hasAnyRole(['admin', 'adver']) ? route('home.campaignslc') : route('home.widgetslc') }}",
            beforeSend: function (request) {
                request.setRequestHeader("token", $('meta[name="csrf-token"]').attr('content'));
            }
        }).done(function (data) {
            var clicks = [];
            var impressions = [];
            var revenues = [];
            $.each(data, function (index, value) {
                clicks.push([value.month, value.clicks]);
                impressions.push([value.month, value.impressions]);
                revenues.push([value.month, value.revenues]);
            });
            CompCharts.widgetsChartLine(clicks, impressions, revenues);
        });
    });
</script>
<script src="{{ asset('js/Chart.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            dataType: "json",
            accepts: "application/json",
            method: 'GET',
            url: '{{ Auth::user()->hasAnyRole(["admin", "adver"]) ? route("home.dailycampaigns") : route("home.dailywidgets") }}'
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
            let myChart = new Chart($('#widgetsDaily'), {
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
<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="block-title">
                <h2>Estatísticas diárias deste mês ({{ Auth::user()->hasAnyRole(['admin', 'adver']) ? 'Despesas' : 'Lucros' }})</h2>
            </div>
            <canvas id="widgetsDaily"></canvas>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="block-title">
                <h2>Tabela com dados gerais</h2>
            </div>
            <div class="table-responsive">
                <table id="datatable-dashboard" class="table table-vcenter table-borderbottom table-condensed">
                    <thead>
                        <tr class="block-title">
                            <th class="text-center">DATA</th>
                            <th class="text-center">CLICKS</th>
                            <th class="text-center">IMPRESSIONS</th>
                            <th class="text-center">REVENUES</th>
                            <th class="text-center">{{ Auth::user()->hasAnyRole(['admin', 'adver']) ? 'CAMPANHAS' : 'WIDGETS' }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        App.datatables();
        $('#datatable-dashboard').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{!! Auth::user()->hasAnyRole(["admin", "adver"]) ? route("campaingns.dashtable") : route("widgets.dashtable") !!}',
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
                {data: 'created_at', name: 'created_at'},
                {data: 'clicks', name: 'clicks'},
                {data: 'impressions', name: 'impressions'},
                {data: 'revenues', name: 'revenues'},
                {data: 'name', name: 'name'},
            ],
        });
    });
    $('.dataTables_filter input').attr('placeholder', 'Buscar');
</script>
@if (Auth::user()->hasRole('admin'))
<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="block-title">
                <h2>Solicitações de pagamento</h2>
            </div>
            <div class="table-responsive">
                <table id="datatable-payments" class="table table-vcenter table-borderbottom table-condensed">
                    <thead>
                        <tr class="block-title">
                            <th class="text-center">DATA</th>
                            <th class="text-center">NOME</th>
                            <th class="text-center">FORMA</th>
                            <th class="text-center">VALOR BRUTO</th>
                            <th class="text-center">VALOR PAGO</th>
                            <th class="text-center">TAXA</th>
                            <th class="text-center">VALOR LÍQUIDO</th>
                            <th class="text-center">STATUS</th>
                            <th class="text-center">INFO</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#datatable-payments').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{!! route("home.payments") !!}',
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
                {data: 'created_at', name: 'created_at'},
                {data: 'user.name', name: 'user.name'},
                {data: 'payment_form', name: 'payment_form'},
                {data: 'brute_value', name: 'brute_value'},
                {data: 'paid_value', name: 'paid_value' },
                {data: 'taxa', name: 'user.taxa', orderable: false, searchable: false},
                {data: 'liquid_value', name: 'liquid_value', orderable: false, searchable: false},
                {data: 'status', name: 'status'},
                {data: 'show', name: 'show', orderable: false, searchable: false},
            ],
        });
    });
</script>
@endif @endauth @endsection