@extends('layouts.template') 
@section('title', 'Pagamentos')
@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li>
        <a href="{{ route('home') }}">Dashboard</a>
    </li>
    <li>
        <a href="{{ route('payments.show', $payment->id) }}">Exibir pagamento</a>
    </li>
    <li>
        <a href="#">Comrpovante de pagamento</a>
    </li>
</ul>

<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="fa fa-file-pdf-o"></i>
                Enviar comprovante de <b>Pagamento</b>
            </h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="block">
            {!! Form::model($payment,['method' => 'patch','route'=>['payments.send_voucher',$payment->id], 'files' => true]) !!}
                @if ($payment->pdf != '')
                    <div class="alert alert-warning alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>	
                        Pagamento já tem comprovante registrado no sistema.
                    </div>
                @endif
                <div class="form-group {{ $errors->has('file_uploaded') ? ' has-error' : '' }}">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-file-pdf-o"></i>
                        </span>
                        {!! Form::file('file_uploaded',['class'=>'form-control input-lg', 'placeholder' => 'Comprovante em .pdf', 'accept'=>'.pdf', 'required']) !!}
                    </div>
                    @if ($errors->has('file_uploaded'))
                    <span class="help-block">
                        <strong>{{ $errors->first('file_uploaded') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group form-actions text-center">
                    {!! Form::submit('ENVIAR ARQUIVO', ['class' => 'btn btn-md btn-default']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop
