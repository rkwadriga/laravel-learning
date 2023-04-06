<?php
/**
 * @var App\Models\Post $post
 */
?>

@extends('layouts.content')

@section('content')
    <h1>Update the Post #{{ $post->id }}</h1>

    @include('post.__form')
    <br />
    @include('post.__delete')
@endsection
