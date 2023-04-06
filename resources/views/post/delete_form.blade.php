<?php
/**
 * @var App\Models\Post $post
 */
?>

<form method="post" action="{{ route('post.delete', $post->id) }}">
    @csrf
    @method("DELETE")

    <input type="submit" value="Delete" />
</form>
