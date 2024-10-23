<script setup>
import Paginator from 'primevue/paginator';
import Posts from '@/Pages/Posts/Partials/Posts.vue';
import PostsLayout from '@/Layouts/PostsLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';

const props = defineProps({
    auth_user: {
        type: Boolean,
        required: true
    },
    posts: {
        type: Array,
        required: true
    },
    links: {
        type: Object,
        required: true,
    }
});

const first = ref(props.links.from - 1);

const form = useForm({
    keywords: props.post_fragment ?? ''
});

const search = () => {
    router.visit(route('posts.search', { fragment: form.keywords }));
};
</script>

<template>
    <Head title="Recent Posts" />

    <PostsLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Recent Posts
            </h2>
        </template>

        <Paginator :first="first" :rows="10" :totalRecords="links.total"
                   template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
                   @page="(e) => router.visit(route('posts.index', { page: e.page + 1 }))">
            <template #start>
                <Link :href="route('posts.index')" class="font-semibold">
                    Everybody's Recent Posts
                </Link>
            </template>
            <template #end>
                <div>
                    <TextInput
                        id="keywords"
                        ref="keywordsInput"
                        v-model="form.keywords"
                        type="keywords"
                        class="mr-1 px-1 pb-1 block w-1/2 inline-flex border"
                        placeholder="keywords"
                        @keyup.enter="search"
                    />
                    <Link href="#search"
                          @click.prevent="search"
                          class="inline-flex items-center mr-2 px-4 py-2 bg-gray-800 border border-transparent rounded-md
                                      font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                                      focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2
                                      focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Search</Link>
                </div>
            </template>
        </Paginator>

        <Posts :posts="posts" :auth_user="auth_user" />

        <Paginator v-if="posts.length" :first="first" :rows="10" :totalRecords="links.total"
                   template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
                   @page="(e) => router.visit(route('posts.index', { page: e.page + 1 }))">
            <template #start>
                <Link :href="route('posts.index')" class="font-semibold">
                    Everybody's Recent Posts
                </Link>
            </template>
            <template #end>
                <div>
                    <TextInput
                        id="keywords"
                        ref="keywordsInput"
                        v-model="form.keywords"
                        type="keywords"
                        class="mr-1 px-1 pb-1 block w-1/2 inline-flex border"
                        placeholder="keywords"
                        @keyup.enter="search"
                    />
                    <Link href="#search"
                          @click.prevent="search"
                          class="inline-flex items-center mr-2 px-4 py-2 bg-gray-800 border border-transparent rounded-md
                                      font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                                      focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2
                                      focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Search</Link>
                </div>
            </template>
        </Paginator>

    </PostsLayout>
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
