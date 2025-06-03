@extends('statamic::layout')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="flex-1">{{ $title }}</h1>
        @yield('actions')
    </div>

    <div class="card">
        @yield('main')
    </div>
@stop 
