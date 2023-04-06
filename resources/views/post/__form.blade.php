<?php
/**
 * @var App\Models\Post|null $post
 */

if (!isset($post)) {
    $post = null;
    $url = route('post.create');
    $method = 'POST';
    $submitBtnText = 'Create';
} else {
    $url = route('post.update', $post->id);
    $method = 'PUT';
    $submitBtnText = 'Update';
}
?>

{!! Form::open(['url' => $url, 'method' => $method]) !!}
    <div class="form-group">
        {!! Form::label('Title') !!}
        {!! Form::text('title', $post?->title, ['class' => 'form-control', 'placeholder' => 'Title']) !!}
    </div>
    <br />
    <div class="form-group">
        {!! Form::label('Content') !!}
        {!! Form::textarea('content', $post?->content, ['class' => 'form-control', 'placeholder' => 'Content']) !!}
    </div>
    <br />
    <div class="form-group">
        {!! Form::submit($submitBtnText, ['class' => 'btn btn-primary']) !!}
    </div>
{!! Form::close() !!}
