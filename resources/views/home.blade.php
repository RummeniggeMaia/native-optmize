@extends('layouts.template')

@section('content')
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

<!-- Styles -->
<div class="body">
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="title m-b-md" style="font-weight: 100;">
                Native Optimize
            </div>
            @auth
            <div class="links">
                @If (Auth::user()->hasRole('admin'))
                <a href="{{ route('users') }}">Users</a>
                <a href="{{ route('creatives') }}">Creatives</a>
                <a href="{{ route('campaingns') }}">Campaigns</a>
                <a href="{{ route('categories') }}">Categories</a>
                @else
                <a href="{{ route('widgets') }}">Widgets</a>
                @endif
            </div>
            <br />
            @If (Auth::user()->hasRole('user'))
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Widget</th>
                        <th>Clicks</th>
                        <th>Impressions</th>
                        <th>Revenues</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($widgets as $widget)
                    <tr style="opacity: 1">
                        <td>{{ $widget->name }}</td>
                        <td>{{ $widget->creativeLogs->sum('clicks') }}</td>
                        <td>{{ $widget->creativeLogs->sum('impressions') }}</td>
                        <?php $revenues = 0; ?>
                        <td>
                            {{ 0 }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            @endauth
        </div>
    </div>
</div>
@endsection
