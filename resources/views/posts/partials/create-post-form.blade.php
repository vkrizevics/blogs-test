@props(['post' => null])
<section>
    <form method="post" action="{{ route('posts.store') }}" class="mt-6 space-y-6">
        @csrf

        @include('posts.partials.edit-post-form-fields')
    </form>
</section>
