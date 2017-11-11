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
            @auth
            <div class="links">
                <a href="{{ route('creatives.index') }}">Creatives</a>
                <a href="{{ route('campaingns.index') }}">Campaings</a>
                <a href="{{ route('widgets.index') }}">Widgets</a>
                <a href="{{ route('categories.index') }}">Categorias</a>
            </div>
            @endauth
        </div>
    </div>
</div>
@endsection
