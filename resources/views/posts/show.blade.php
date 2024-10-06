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
                           class="underline underline-offset-2 hover:text-sky-500">Edit</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
