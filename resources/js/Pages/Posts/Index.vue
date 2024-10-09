<script setup>
import PostsLayout from '@/Layouts/PostsLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

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
                            <Link :href="post.show_link"
                               class="underline underline-offset-2 hover:text-sky-500">{{ post.comments.length }} comments</Link>
                        </div>

                        <div v-if="auth_user">
                            <Link v-if="post.is_author" href="post.edit"
                               class="inline-flex items-center mr-2 px-4 py-2 bg-gray-800 border border-transparent rounded-md
                                  font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                                  focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2
                                  focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Edit</Link>

                            <a v-if="post.is_author" href="#destroy" class="inline-flex items-center mr-2 px-4 py-2 bg-gray-800 border border-transparent rounded-md
                                  font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700
                                  focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2
                                  focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                               :onclick="post.delete_form_onclick">Delete</a>

                            <form v-if="post.is_author" :id="post.delete_form_id" method="post"
                                  :action="post.destroy" class="hidden">
                                <input type="hidden" name="_token" :value="csrf_token">
                                <input type="hidden" name="_method" value="DELETE">
                            </form>

                            <Link :href="post.comment_link"
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
