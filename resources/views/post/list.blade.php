<?php
/**
 * @var App\Models\Post[] $posts
 */

use App\Http\Controllers\PostController;
?>

@extends('layouts.content')

@section('content')
    <a href="{{ route('post.create') }}">Create a new post</a>

    <ul>
        @foreach ($posts as $post)
            <li>
                @if($post->getPhoto() !== null)
                    <img src="{{ $post->getPhoto() }}" alt="{{ basename($post->getPhoto()) }}">
                @endif
                <a href="{{ route('post.view', $post->id) }}">
                    ID: {{ $post->id }}, Title: {{ $post->title }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection
