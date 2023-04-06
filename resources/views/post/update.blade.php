<?php
/**
 * @var App\Models\Post $post
 */
?>

@extends('layouts.content')

@section('content')
    <h1>Update the Post #{{ $post->id }}</h1>

    <form method="post" action="{{ route('post.update', $post->id) }}">
        @csrf
        @method("PUT")

        <input type="text" name="title" placeholder="Title" value="{{ $post->title }}" />
        <br />
        <br />
        <textarea name="content" cols="30" rows="10" placeholder="Content">{{ $post->content }}</textarea>
        <br />
        <br />
        <input type="submit" name="submit" title="Update" value="Update" />
    </form>

    <br />
    @include('post.delete_form')
@endsection
