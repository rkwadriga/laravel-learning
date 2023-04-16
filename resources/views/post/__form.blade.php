<?php
/**
 * @var App\Models\Post|null $post
 * @var Illuminate\Support\ViewErrorBag $errors
 */

if (!isset($post)) {
    $post = null;
    $url = route('post.store');
    $method = 'POST';
    $submitBtnText = 'Create';
} else {
    $url = route('post.edit', $post->id);
    $method = 'PUT';
    $submitBtnText = 'Update';
}
?>

{!! Form::open(['url' => $url, 'method' => $method, 'files' => true]) !!}
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
        {!! Form::file('photo', ['class' => 'form-control', 'placeholder' => 'Photo']) !!}
    </div>
    <br />
    <div class="form-group">
        {!! Form::submit($submitBtnText, ['class' => 'btn btn-primary']) !!}
    </div>
{!! Form::close() !!}

@if ($errors->count() > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
