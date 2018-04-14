@extends('layouts/template')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="{{ route('creatives') }}">Lista de Anúncios</a></li>
    <li><a href="">Exibir Anúncios</a></li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-bullhorn"></i>Exibir <b>Anúncio</b>
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
                        <span class="input-group-addon"><i class="fa fa-image"></i></span>
                        <img class="img-fluid img-thumbnail" src="{{ asset($creative->image) }}">
                    </div>
                </div>
                 <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input title="Nome" type="text" class="form-control" id="isbn" value="{{ $creative->name }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tags"></i></span>
                        <input title="Marca" type="text" class="form-control" id="brand" value="{{ $creative->brand }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                        <input title="URL" type="text" class="form-control" id="title" value="{{ $creative->url }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="lnr lnr-list"></i></span>
                        <input title="Categoria" type="text" class="form-control" id="category" value="{{ $creative->category->name }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-mouse-pointer"></i></span>
                        <input title="Clicks" type="text" class="form-control" id="clicks" value="{{ $clicks }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-eye"></i></span>
                        <input title="Visualizações" type="text" class="form-control" id="impressions" value="{{ $impressions }}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="lnr lnr-chart-bars"></i></span>
                        <input title="CTR" type="text" class="form-control" id="ctr" value="@If($clicks > 0){{ number_format(($clicks / $impressions * 100), 2) }}@else 0.00 @endif" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-vcenter table-borderbottom table-condensed">
                            <thead>
                                <tr class="bg-info">
                                    <th>CLICK ID</th>
                                    <th>WIDGET</th>
                                </tr>
                            </thead>
                        </table>
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
                url: '{!! route("creatives.clicks", $creative->id) !!}',
                type: 'GET',
                'beforeSend': function (request) {
                    request.setRequestHeader("token", $('meta[name="csrf-token"]').attr('content'));
                }
            },
            columns: [
                {data: 'click_id', name: 'click_id'},
                {data: 'widget.name', name: 'widget.name'},
            ],

        });
        $('.dataTables_filter input').attr('placeholder', 'Buscar');
    });
</script>
@stop
