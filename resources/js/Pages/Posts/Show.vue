<script setup>
import PostsLayout from '@/Layouts/PostsLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Paginator from "primevue/paginator";

defineProps({
    auth_user: {
        type: Boolean,
        required: true
    },
    post: {
        type: Object,
        required: true
    }
});

const links = {
    total: 10
}
</script>

<template>
    <Head :title="post.title" />

    <PostsLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                {{ post.title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-6">

                    <div class="divide-y-4">
                        <h2 class="text-xl bg-white leading-tight">
                            <Link :href="route('posts.user', { name: post.user.name.split(' ').join('_') })"
                                  class="font-semibold">{{ post.user.name }}</Link>
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
                        {{ post.comments.length }} comments
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

                    <div v-for="comment in post.comments">
                        <div class="font-semibold">
                            <Link :href="route('posts.user', { name: comment.user.name.split(' ').join('_') })"
                                  class="font-semibold">{{ comment.user.name }}</Link>
                        </div>

                        {{ comment.content }}

                        <a v-if="comment.is_author" href="#destroy-comment"
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md
                              font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                              focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2
                              focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 float-right"
                           @click.prevent="router.delete(route('comments.destroy', { comment: comment.id }))">X</a>
                    </div>
                </div>
            </div>
        </div>
    </PostsLayout>
</template>
