@extends('layouts.app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
    @yield('content')
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
