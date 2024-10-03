<section>
    <form method="post" action="{{ route('posts.store') }}" class="mt-6 space-y-6">
        @csrf

        <div>
            <x-input-label for="title" :value="__('Title')" />
            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" value="" required autofocus autocomplete="title" />
            <x-input-error class="mt-2" messages="" />
        </div>

        <div>
            <x-input-label for="content" :value="__('Contents')" />
            <x-text-textarea id="content" name="content" type="content" class="mt-1 block w-full" value="" :rows="7" required autocomplete="content" />
            <x-input-error class="mt-2" messages="" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
