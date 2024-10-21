<script setup>
import Paginator from 'primevue/paginator';
import PostsLayout from '@/Layouts/PostsLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {nextTick, ref, useTemplateRef} from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

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
        required: true
    },
    category: {
        type: Object,
        required: true
    }
});

const first = ref(props.links.from - 1);

const form = useForm({
    keywords: ''
});

const showingSearch = ref(false);
const keywordsInput = ref(null);

const showSearch = () => {
    showingSearch.value = true;

    // nextTick(() => keywordsInput.value.focus());
}

const closeModal = () => {
    showingSearch.value = false;
}

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
                   @page="(e) => router.visit(route('category.show', { category: category.name.split(' ').join('').toLowerCase(), page: e.page + 1 }))">
            <template #start>
                <Link :href="route('category.show', { category: category.name.split(' ').join('').toLowerCase() })"
                      class="font-semibold bg-indigo-600 text-indigo-100 me-2 px-2.5 py-1 rounded-full">{{ category.name }}</Link>
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

        <template v-for="post in posts">
            <div class="pt-12">
                <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-6">

                        <div class="divide-y-4">
                            <h2 class="text-xl bg-white leading-tight">
                                {{ post.title }}
                            </h2>
                            <div class="text-sm">
                                {{ post.created_at_formatted }}
                            </div>
                        </div>

                        <div v-html="post.escaped_content"></div>

                        <div>
                            <span v-for="category in post.categories"
                                  class="bg-indigo-600 text-indigo-100 text-sm font-medium me-2 px-2.5 py-1 rounded-full">
                                <Link :href="route('category.show', { category: category.name.split(' ').join('').toLowerCase() })"
                                      class="font-semibold">{{ category.name }}</Link></span>
                        </div>

                        <div>
                            <Link :href="route('posts.show', {post: post.id })"
                               class="underline underline-offset-2 hover:text-sky-500">{{ post.comments.length }} comments</Link>
                        </div>

                        <div v-if="auth_user">
                            <Link v-if="post.is_author" :href="route('posts.edit', { post: post.id })"
                               class="inline-flex items-center mr-2 px-4 py-2 bg-gray-800 border border-transparent rounded-md
                                  font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                                  focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2
                                  focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Edit</Link>

                            <Link v-if="post.is_author" href="#destroy" class="inline-flex items-center mr-2 px-4 py-2 bg-gray-800 border border-transparent rounded-md
                                  font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                                  focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2
                                  focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                               @click.prevent="router.delete(route('posts.destroy', { post: post.id }))">Delete</Link>

                            <Link :href="route('posts.comments.create', { post: post.id })"
                               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md
                                  font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                                  focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2
                                  focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Comment</Link>
                        </div>

                    </div>
                </div>
            </div>
        </template>
        <template v-if="!posts.length">
            <div class="py-12">
                <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-6">

                        <div class="divide-y-4">
                            <h2 class="text-xl bg-white leading-tight">
                                No posts yet.
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <Paginator v-if="posts.length" :first="first" :rows="10" :totalRecords="links.total" class="pt-12"
                   template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
                   @page="(e) => router.visit(route('category.show', { category: category.name.split(' ').join('').toLowerCase(), page: e.page + 1 }))">
            <template #start>
                <Link :href="route('category.show', { category: category.name.split(' ').join('').toLowerCase() })"
                      class="font-semibold bg-indigo-600 text-indigo-100 me-2 px-2.5 py-1 rounded-full">{{ category.name }}</Link>
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
