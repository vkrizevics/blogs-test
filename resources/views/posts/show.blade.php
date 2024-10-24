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

                @if (Auth::check() && $post->user->id = Auth::id())
                    <div>
                        <a href="{{ route('posts.edit', ['post' => $post->id]) }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md
                              font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                              focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2
                              focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Edit</a>

                        <a href="#destroy" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md
                              font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                              focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2
                              focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                           onclick="document.getElementById('delete-form-{{$post->id}}').submit();">Delete</a>

                        <form id="delete-form-{{$post->id}}" method="post"
                              action="{{ route('posts.destroy', ['post' => $post->id]) }}" class="hidden">
                            @csrf
                            @method('delete')
                        </form>

                        <a href="{{ route('posts.comments.create', ['post' => $post->id]) }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md
                              font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                              focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2
                              focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Comment</a>
                    </div>
                @endif

                <div>
                    {{ count($post->comments) }} comments
                </div>

                @foreach($post->comments as $comment)
                    <div>
                        {{ $comment->content }}

                        <a href="#destroy-comment"
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md
                              font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                              focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2
                              focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 float-right"
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
