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

const props = defineProps({
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
    },
    links: {
        type: Object,
        required: true,
    }
});

const form = useForm({
    keywords: ''
});

const showingSearch = ref(false);
const keywordsInput = ref(null);

const first = useTemplateRef(props.current_page);

const showSearch = () => {
    showingSearch.value = true;

    // nextTick(() => keywordsInput.value.focus());
}

const closeModal = () => {
    showingSearch.value = false;
}

const search = () => {

};
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

        <Paginator v-model:first="first" :rows="10" :totalRecords="links.total"
                   template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
                   @page="(e) => router.visit(route('posts.index', {page: e.page}))">
            <template #start>
            </template>
            <template #end>
                <Link href="#"
                      class="inline-flex items-center mr-2 px-4 py-2 bg-gray-800 border border-transparent rounded-md
                                  font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                                  focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2
                                  focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                      @click.prevent="showSearch">Search</Link>

            </template>
        </Paginator>

        <Modal :show="showingSearch" @close="closeModal">
            <div class="p-6">
                <!--<h2
                    class="text-lg font-medium text-gray-900"
                >
                    Are you sure you want to delete your account?
                </h2>-->

                <div class="mt-6">
                    <InputLabel
                        for="keywords"
                        value="Keywords"
                    />

                    <TextInput
                        id="keywords"
                        ref="keywordsInput"
                        v-model="form.keywords"
                        type="keywords"
                        class="mt-1 block w-3/4"
                        placeholder="Keywords"
                        @keyup.enter="search"
                    />

                    <!--<InputError :message="form.errors.keywords" class="mt-2" />-->
                </div>

                <!--<div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">
                        Cancel
                    </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Delete Account
                    </DangerButton>
                </div>-->
            </div>
        </Modal>

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
