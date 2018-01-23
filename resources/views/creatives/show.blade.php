@extends('layouts/template')

@section('content')
<h1>Dados do Creative</h1>

<form class="form-horizontal">
    <div class="form-group">
        <label for="image" class="col-sm-2 control-label">Image</label>
        <div class="col-sm-10">
            <img src="{{ asset($creative->image) }}" height="180" width="180" class="img-rounded">
        </div>
    </div>
    <div class="form-group">
        <label for="isbn" class="col-sm-2 control-label">Name</label>
        <div class="col-sm-10">
            <input type="text" style="background-color:white" class="form-control" id="isbn" value="{{ $creative->name }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">URL</label>
        <div class="col-sm-10">
            <input type="text" style="background-color:white" style="background-color:white" class="form-control" id="title" value="{{ $creative->url }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">Category</label>
        <div class="col-sm-10">
            <input type="text" style="background-color:white" class="form-control" id="category" value="{{ $category->name }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">Clicks</label>
        <div class="col-sm-10">
            <input type="text" style="background-color:white" class="form-control" id="clicks" value="{{ count($clicks) }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">Impressions</label>
        <div class="col-sm-10">
            <input type="text" style="background-color:white" class="form-control" id="impressions" value="{{ $impressions }}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label">CTR</label>
        <div class="col-sm-10">
            <input type="text" style="background-color:white" class="form-control" id="ctr" value="@If($clicks > 0){{ number_format($impressions / count($clicks), 2) }}@else 0.00 @endif" readonly>
        </div>
    </div>
    <div class="form-group">
        <label for="clicks" class="col-sm-2 control-label">Clicks</label>
        <div class="col-sm-10">
            <table class="table table-striped table-bordered table-hover ">
                <thead>
                    <tr class="bg-info">
                        <th>click_id</th>
                        <th>Creative</th>
                        <th>Widget</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clicks as $click)
                    <tr>
                        <td>{{ $click->click_id }}</td>
                        <td>{{ $click->creative->name }}</td>
                        <td>{{ $click->widget->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <a href="{{ route('creatives')}}" class="btn btn-primary">Voltar</a>
        </div>
    </div>
</form>
@stop
