@extends('layouts/template')
@section('title', 'Pagamentos')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="">Lista de Pagamentos</a></li>
</ul>
<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-money"></i>Lista de <b>Pagamentos</b>
            </h1>
        </div>
    </div>
</div>
<div class="row"> 
    <div class="col-md-12">             
        <div class="table-responsive">
            <table id="datatable" class="table table-vcenter table-borderbottom table-condensed">
                <thead>
                    <tr class="block-title">
                        <th class="text-center">DATA</th>
                        <th class="text-center">FORMA</th>
                        <th class="text-center">VALOR</th>
                        <th class="text-center">PAGO</th>
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
                url: '{!! route("payments.data") !!}',
                type: 'GET',
                'beforeSend': function (request) {
                    request.setRequestHeader("token", $('meta[name="csrf-token"]').attr('content'));
                }
            },
            columns: [
                {data: 'created_at', name: 'created_at'},
                {data: 'payment_form', name: 'payment_form'},
                {data: 'brute_value', name: 'brute_value'},
                {data: 'paid_value', name: 'paid_value'},
                {data: 'status', name: 'status'},
                {data: 'info', name: 'info', orderable: false, searchable: false},
            ],

        });
        $('.dataTables_filter input').attr('placeholder', 'Buscar');
    });
</script>
@endsection
