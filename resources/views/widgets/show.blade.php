@extends('layouts/template')
@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('home') }}">Home</a></li>
    <li><a href="#">Exibir Widgets</a></li>
</ul>
<div class="row">
    <div class="col-lg-12 content-header">
        <div class="header-section">
            <h1>
                <i class="lnr lnr-power-switch"></i>Dados do <b>Widget</b><br><small>Este é seu painel, cuide bem dele :)</small>
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
                        <input type="text" class="form-control" id="type"
                               placeholder="{{ [
                                    '0' => '----------------------',
                                    '1' => 'Barra Lateral Direita',
                                    '2' => 'Barra Lateral Esquerda',
                                    '3' => 'Central'
                                ][$widget->type] }}" readonly>
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
                        {{ Form::textarea('code', $code, ['class'=>'form-control textarea', 'rows' => 3]) }}
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-md-12">             
                        <div class="table-responsive">
                            <table class="table table-vcenter table-borderbottom table-condensed">
                                <thead>
                                    <tr class="bg-info">
                                        <th>Creative</th>
                                        <th>Image</th>
                                        <th>Clicks</th>
                                        <th>Impressions</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($widget->creativeLogs as $log)
                                    <tr>
                                        <td>{{ $log->creative->name }}</td>
                                        <td><img src="{{ asset($log->creative->image) }}" height="154" width="128"></td>
                                        <td>{{ $log->clicks }}</td>
                                        <td>{{ $widget->impressions }}</td>
                                        <td>R$ {{ $log->revenues }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
