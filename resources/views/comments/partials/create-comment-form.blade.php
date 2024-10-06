@props(['post' => null])
<section>
    <form method="post" action="{{ route('posts.comments.store', ['post' => $post->id]) }}" class="mt-6 space-y-6">
        @csrf

        @include('comments.partials.edit-comment-form-fields')
    </form>
</section>
