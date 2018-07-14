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
         {!! Form::button('<i class="fa fa-pause"></i>&nbsp;&nbsp;&nbsp;&nbsp;PAUSAR CAMPANHAS', ['type' => 'submit', 'class' => 'btn btn-xs btn-warning btn-block']) !!}
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
@if (Auth::user()->hasRole('admin'))
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="datatable" class="table table-vcenter table-borderbottom table-condensed">
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
<script type="text/javascript">
    $(document).ready(function () {
        App.datatables();
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{!! route("home.payments") !!}',
                type: 'GET',
                'beforeSend': function (request) {
                    request.setRequestHeader("token", $('meta[name="csrf-token"]').attr('content'));
                }
            },
            columns: [{
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'user.name',
                    name: 'user.name'
                },
                {
                    data: 'payment_form',
                    name: 'payment_form'
                },
                {
                    data: 'brute_value',
                    name: 'brute_value'
                },
                {
                    data: 'paid_value',
                    name: 'paid_value'
                },
                {
                    data: 'taxa',
                    name: 'user.taxa',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'liquid_value',
                    name: 'liquid_value',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'show',
                    name: 'show',
                    orderable: false,
                    searchable: false
                },
            ],

        });
        $('.dataTables_filter input').attr('placeholder', 'Buscar');
    });
</script>
@endif @endauth @endsection