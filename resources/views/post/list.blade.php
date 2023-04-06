<?php
/**
 * @var App\Models\Post[] $posts
 */

use App\Http\Controllers\PostController;
?>

@extends('layouts.content')

@section('content')
    <a href="{{ route('post.create_form') }}">Create a new post</a>

    <ul>
        @foreach ($posts as $post)
            <li>
                <a href="{{ route('post.view', $post->id) }}">
                    ID: {{ $post->id }}, Title: {{ $post->title }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection
