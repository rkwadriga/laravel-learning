<?php
/**
 * @var App\Models\Post $post
 */
?>

{!! Form::open(['url' => route('post.delete', $post->id), 'method' => 'DELETE']) !!}
    <div class="form-group">
        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
    </div>
{!! Form::close() !!}
