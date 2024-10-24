@props(['comment' => null])
<div>
    <x-input-label for="content" :value="__('Comment')" />
    <x-text-textarea id="content" name="content" type="content" class="mt-1 block w-full" required autocomplete="content"
                     :rows="7" :value="old('content', isset($comment) ? $comment->content : '')" />
    <x-input-error class="mt-2" :messages="$errors->get('content')" />
</div>

<div class="flex items-center gap-4">
    <x-primary-button>{{ __('Save') }}</x-primary-button>
</div>
