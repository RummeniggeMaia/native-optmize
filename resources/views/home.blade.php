@extends('layouts.template')
@section('title', 'Dashboard')

@section('content')
@auth
@If (Auth::user()->hasRole('user'))
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
</ul>
<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-tv"></i>Estatísticas por <b>Widgets</b>
            </h1>
        </div>
    </div>
</div>
<div class="row animation-fadeInQuick">
    <div class="col-sm-6 col-lg-3">
        <a href="#" class="widget widget-hover-effect1">
            <div class="widget-simple">
                <div class="widget-icon pull-left themed-background-default animation-fadeIn">
                    <i class="gi gi-wallet"></i>
                </div>
                <h3 class="widget-content text-right animation-pullDown">
                    <strong>R$ -1,20</strong>
                    <small>Disponível</small>
                </h3>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="page_ready_inbox.html" class="widget widget-hover-effect1">
            <div class="widget-simple">
                <div class="widget-icon pull-left themed-background-default animation-fadeIn">
                    <i class="gi gi-undo"></i>
                </div>
                <h3 class="widget-content text-right animation-pullDown">
                    <strong>R$ 875,85</strong>
                    <small>Transferido</small>
                </h3>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="page_ready_inbox.html" class="widget widget-hover-effect1">
            <div class="widget-simple">
                <div class="widget-icon pull-left themed-background-default animation-fadeIn">
                    <i class="gi gi-clock"></i>
                </div>
                <h3 class="widget-content text-right animation-pullDown">
                    <strong>R$ 0,00</strong>
                    <small>Aguardando</small>
                </h3>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="page_ready_inbox.html" class="widget widget-hover-effect1">
            <div class="widget-simple">
                <div class="widget-icon pull-left themed-background-default animation-fadeIn">
                    <i class="gi gi-money"></i>
                </div>
                <h3 class="widget-content text-right animation-pullDown">
                    <strong>R$ -1,20</strong>
                    <small>Total</small>
                </h3>
            </div>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-sm-8">
        <div class="block">
            <div class="block-title">
                <h2>Geral</h2>
            </div>    
            <div id="widgetsChartLine" class="chart" style="padding: 0px; position: relative;"><canvas class="flot-base" width="500" height="410" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 500px; height: 410px;"></canvas><div class="flot-text" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; font-size: smaller; color: rgb(84, 84, 84);"><div class="flot-x-axis flot-x1-axis xAxis x1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;"><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 41px; top: 395px; left: 22px; text-align: center;">Fev</div><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 41px; top: 395px; left: 172px; text-align: center;">Mar</div><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 41px; top: 395px; left: 325px; text-align: center;">Abr</div><div class="flot-tick-label tickLabel" style="position: absolute; max-width: 41px; top: 395px; left: 477px; text-align: center;">Mai</div></div><div class="flot-y-axis flot-y1-axis yAxis y1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;"><div class="flot-tick-label tickLabel" style="position: absolute; top: 383px; left: 19px; text-align: right;">0</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 257px; left: 6px; text-align: right;">500</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 132px; left: 0px; text-align: right;">1000</div><div class="flot-tick-label tickLabel" style="position: absolute; top: 7px; left: 0px; text-align: right;">1500</div></div></div><canvas class="flot-overlay" width="500" height="410" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 500px; height: 410px;"></canvas><div class="legend"><div style="position: absolute; width: 112px; height: 56px; top: 24px; left: 45px; background-color: rgb(255, 255, 255); opacity: 0.85;"> </div><table style="position:absolute;top:24px;left:45px;;font-size:smaller;color:#545454"><tbody><tr><td class="legendColorBox"><div style="border:1px solid #ccc;padding:1px"><div style="width:4px;height:0;border:5px solid rgb(52,152,219);overflow:hidden"></div></div></td><td class="legendLabel">Conversões</td></tr><tr><td class="legendColorBox"><div style="border:1px solid #ccc;padding:1px"><div style="width:4px;height:0;border:5px solid rgb(51,51,51);overflow:hidden"></div></div></td><td class="legendLabel">Desistências</td></tr></tbody></table></div></div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="block">
            <div class="block-title">
                <h2>Lucros diários</h2>
            </div>
        </div>
    </div>
</div>
<!-- TODO Mudar versao do javascript -->
<script src="{{ asset('pago/js/pages/compCharts.js?v8') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
    $.ajax({
        dataType: "json",
        accepts: "application/json",
        method: 'GET',
        url: '{!! route("widgets.linechart") !!}',
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
@elseif (Auth::user()->hasRole('admin'))

@endif
@endauth
@endsection
