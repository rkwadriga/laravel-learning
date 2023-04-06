<?php
/**
 * @var App\Models\Post $post
 */
?>

@extends('layouts.content')

@section('content')
    <h1>ID: {{ $post->id }}</h1>
    <h2>Title: {{ $post->title }}</h2>
    <p>{{ $post->content }}</p>
    <a href="{{ route('post.update_form', $post->id) }}">Update</a>

    <br />
    <br />
    @include('post.delete_form')
@endsection


