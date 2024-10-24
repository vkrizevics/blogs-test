@props(['post' => null])
<div>
    <x-input-label for="title" :value="__('Title')" />
    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" required autofocus autocomplete="title"
                  :value="old('title', isset($post) ? $post->title : '')" />
    <x-input-error class="mt-2" :messages="$errors->get('title')" />
</div>

<div>
    <x-input-label for="content" :value="__('Content')" />
    <x-text-textarea id="content" name="content" type="content" class="mt-1 block w-full" required autocomplete="content"
                     :rows="7" :value="old('content', isset($post) ? $post->content : '')" />
    <x-input-error class="mt-2" :messages="$errors->get('content')" />
</div>

<div class="flex items-center gap-4">
    <x-primary-button>{{ __('Save') }}</x-primary-button>
</div>
