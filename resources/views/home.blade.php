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
                @If (Auth::user()->hasRole('admin'))
                <a href="{{ route('users') }}">Users</a>
                @else
                <a href="{{ route('creatives') }}">Creatives</a>
                <a href="{{ route('campaingns') }}">Campaings</a>
                <a href="{{ route('widgets') }}">Widgets</a>
                @endif
                <a href="{{ route('categories') }}">Categories</a>
            </div>
            @endauth
        </div>
    </div>
</div>
@endsection
