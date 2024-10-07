<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="py-12">
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
                    {{ count($post->comments) }} comments
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

                @foreach($post->comments as $comment)
                    <div>
                        {{ $comment->content }}
                        <a href="#destroy-comment" class="underline underline-offset-2 hover:text-sky-500 float-right"
                           onclick="document.getElementById('delete-form-comment-{{$comment->id}}').submit();">X</a>

                        <form id="delete-form-comment-{{$comment->id}}" method="post"
                              action="{{ route('comments.destroy', ['comment' => $comment->id]) }}" class="hidden">
                            @csrf
                            @method('delete')
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
