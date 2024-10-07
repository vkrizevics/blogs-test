<x-app-layout>
    @foreach($posts as $post)

        <x-slot name="header">
        </x-slot>

        <div class="pt-12 {{ $loop->last ? 'pb-12' : '' }}">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-6">
                    <div class="divide-y-4">
                        <h2 class="font-semibold text-xl bg-white leading-tight">
                            {{ $post->title }} by {{ $post->user->name }}
                        </h2>
                        <div class="text-sm">
                            {{ $post->getCreatedAtFormatted() }}
                        </div>
                    </div>
                    <div>
                        {!! nl2br(htmlspecialchars($post->content), false) !!}
                    </div>

                    <div>
                        <a href="{{ route('posts.show', ['post' => $post->id]) }}"
                           class="underline underline-offset-2 hover:text-sky-500">{{ $post->comments()->count() }} comments</a>

                        <a href="{{ route('posts.comments.create', ['post' => $post->id]) }}"
                           class="underline underline-offset-2 hover:text-sky-500">Add a comment</a>

                        @if (Auth::check() && $post->user->id = Auth::id())
                            <a href="{{ route('posts.edit', ['post' => $post->id]) }}"
                               class="underline underline-offset-2 hover:text-sky-500">Edit the post</a>

                            <a href="#destroy" class="underline underline-offset-2 hover:text-sky-500"
                               onclick="document.getElementById('delete-form-{{$post->id}}').submit();">Delete the post</a>

                            <form id="delete-form-{{$post->id}}" method="post"
                                  action="{{ route('posts.destroy', ['post' => $post->id]) }}" class="hidden">
                                @csrf
                                @method('delete')
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            @if ($loop->last)
                <style>
                    /**
                        text-sky-500 color
                     */
                    .custom-pagination span[aria-current="page"] span {
                        color: rgb(14, 165, 233) !important;
                    }

                    .custom-pagination a:hover {
                        color: rgb(14, 165, 233) !important;
                    }
                </style>
                <div class="custom-pagination max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>

    @endforeach
</x-app-layout>
