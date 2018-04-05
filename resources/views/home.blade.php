@extends('layouts.template')

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
                <i class="fa fa-tv"></i>Estatísticas por <b>Widgets</b><br><small>Este é seu painel, cuide bem dele :)</small>
            </h1>
        </div>
    </div>
</div>
<div class="row"> 
    <div class="col-md-12">             
        <div class="table-responsive">
            <table id="datatable" class="table table-vcenter table-borderbottom table-condensed">
                <thead>
                    <tr>
                        <th>Widget</th>
                        <th>Clicks</th>
                        <th>Impressions</th>
                        <th>Revenues</th>
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
                url: '{!! route("home.data") !!}',
                type: 'GET',
                'beforeSend': function (request) {
                    request.setRequestHeader("token", $('meta[name="csrf-token"]').attr('content'));
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'clicks', name: 'clicks'},
                {data: 'impressions', name: 'impressions'},
                {data: 'revenues', name: 'revenues'},
            ],

        });
    });
</script>
@endif
@endauth
@endsection
