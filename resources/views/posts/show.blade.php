<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-6">
                <div>
                    <h2 class="font-semibold text-xl bg-white leading-tight">
                        {{ $post->title }} by {{ $author->name }}
                    </h2>
                    <div class="text-sm">
                        {{ $post->getCreatedAtFormatted() }}
                    </div>
                </div>
                <div>
                    {{ $post->content }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
