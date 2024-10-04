@props(['post' => null])
<section>
    <form method="post" action="{{ route('posts.update', ['post' => $post->id]) }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        @include('posts.partials.edit-post-form-fields', compact('post'))
    </form>
</section>
