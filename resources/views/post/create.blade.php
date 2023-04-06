@extends('layouts.content')

@section('content')
    <h1>Create a new Post</h1>

    <form method="post" action="{{ route('post.create') }}">
        @csrf

        <input type="text" name="title" placeholder="Title" />
        <br />
        <br />
        <textarea name="content" cols="30" rows="10" placeholder="Content"></textarea>
        <br />
        <br />
        <input type="submit" name="submit" title="Create" />
    </form>
@endsection
