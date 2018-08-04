@extends('layouts/template') @section('title', 'Pagamentos') @section('content')
<ul class="breadcrumb breadcrumb-top">
    <li>
        <a href="{{ route('home') }}">Home</a>
    </li>
    <li>
        <a href="">Lista de Pagamentos</a>
    </li>
</ul>
<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-money"></i>Lista de
                <b>Pagamentos</b>
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
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Informações do pagamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
               <div id="modal-content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
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
            columns: [{
                    data: 'created_at',
                    name: 'created_at'
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
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'info',
                    name: 'info',
                    orderable: false,
                    searchable: false
                },
            ],

        });
        $('.dataTables_filter input').attr('placeholder', 'Buscar');
        $('#exampleModal').on('shown.bs.modal', function(e) {
            $('.modal-body #modal-content').append($(e.relatedTarget).data('info'));
        });
    });
</script>
@endsection