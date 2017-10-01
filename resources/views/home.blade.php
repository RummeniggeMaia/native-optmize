@extends('layouts.template')

@section('content')
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

<!-- Styles -->
<div class="body">
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="title m-b-md">
                Native Optimize
            </div>
            <div class="links">
                <a href="{{ route('creatives.index') }}">Creatives</a>
                <a href="{{ route('campaingns.index') }}">Campaings</a>
                <a href="{{ route('widgets.index') }}">Widgets</a>
            </div>
        </div>
    </div>
</div>
@endsection
