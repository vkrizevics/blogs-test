<script setup>
import Paginator from 'primevue/paginator';
import PostsLayout from '@/Layouts/PostsLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { useTemplateRef } from 'vue';

defineProps({
    csrf_token: {
        type: String,
        required: true
    },
    auth_user: {
        type: Boolean,
        required: true
    },
    posts: {
        type: Array,
        required: true
    }
});

const first = useTemplateRef(0);
</script>

<template>
    <Head title="Recent Posts" />

    <GuestLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Recent Posts
            </h2>
        </template>

        <Paginator v-model:first="first" :rows="10" :totalRecords="120" :rowsPerPageOptions="[10, 20, 30]"
                   template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink" />

        <template v-for="post in posts">
            <div class="pt-12" :class="post.more_classes">
                <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-6">

                        <div class="divide-y-4">
                            <h2 class="font-semibold text-xl bg-white leading-tight">
                                {{ post.title }} by {{ post.user.name }}
                            </h2>
                            <div class="text-sm">
                                {{ post.created_at_formatted }}
                            </div>
                        </div>

                        <div v-html="post.escaped_content"></div>

                        <div>
                            <span v-for="category in post.categories"
                                  class="bg-indigo-600 text-indigo-100 text-sm font-medium me-2 px-2.5 py-1 rounded-full">{{ category.name }}</span>
                        </div>

                        <div>
                            <Link :href="route('posts.show', {post: post.id})"
                               class="underline underline-offset-2 hover:text-sky-500">{{ post.comments.length }} comments</Link>
                        </div>

                        <div v-if="auth_user">
                            <Link v-if="post.is_author" :href="route('posts.edit', {post: post.id})"
                               class="inline-flex items-center mr-2 px-4 py-2 bg-gray-800 border border-transparent rounded-md
                                  font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                                  focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2
                                  focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Edit</Link>

                            <Link v-if="post.is_author" href="#destroy" class="inline-flex items-center mr-2 px-4 py-2 bg-gray-800 border border-transparent rounded-md
                                  font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                                  focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2
                                  focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                               @click.prevent="router.delete(route('posts.destroy', {post: post.id}), {_token: $page.props.csrf_token});">Delete</Link>

                            <Link :href="route('posts.comments.create', {post: post.id})"
                               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md
                                  font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                                  focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2
                                  focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Comment</Link>
                        </div>

                    </div>
                </div>
            </div>
        </template>
    </GuestLayout>
</template>
<style>

.p-paginator-page {
    @apply w-10;
    @apply h-10;
    @apply bg-indigo-100;
    @apply transition-colors;
    @apply duration-150;
    @apply text-indigo-600;
    @apply rounded-full;
    @apply font-semibold;
    @apply text-sm;
    @apply hover:bg-indigo-100;
}

.p-paginator-page-selected {
    @apply w-10;
    @apply h-10;
    @apply rounded-full;
    @apply bg-gray-800;
    @apply border;
    @apply border-transparent;
    @apply font-semibold;
    @apply text-sm;
    @apply text-white;
    @apply uppercase;
    @apply tracking-widest;
    @apply hover:bg-gray-700;
    @apply focus:bg-gray-700;
    @apply active:bg-gray-900;
    @apply focus:outline-none;
    @apply focus:ring-2;
    @apply focus:ring-indigo-500;
    @apply focus:ring-offset-2;
    @apply transition;
    @apply ease-in-out;
    @apply duration-150;
}

.p-paginator-content .p-disabled {
    @apply w-10;
    @apply h-10;
    @apply text-indigo-600;
    @apply transition-colors;
    @apply duration-150;
    @apply bg-white;
    @apply rounded-full;
    @apply hover:bg-indigo-100;
}

</style>
