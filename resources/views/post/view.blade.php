<?php
/**
 * @var App\Models\Post $post
 */
?>

@extends('layouts.content')

@section('content')
    @if($post->getPhoto() !== null)
        <img src="{{ $post->getPhoto() }}" alt="{{ basename($post->getPhoto()) }}">
    @endif
    <h1>ID: {{ $post->id }}</h1>
    <h2>Title: {{ $post->title }}</h2>
    <p>{{ $post->content }}</p>
    <a href="{{ route('post.update', $post->id) }}">Update</a>

    <br />
    <br />
    @include('post.__delete')
@endsection


