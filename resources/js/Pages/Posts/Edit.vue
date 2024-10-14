<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import AutoComplete from 'primevue/autocomplete';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {ref, useTemplateRef} from "vue";

const props = defineProps({
    post: {
        type: Object,
        required: true
    },
    csrf_token: {
        type: String,
        required: true
    },
    auth_user: {
        type: Boolean,
        required: true
    }
});

let itemsArr = ['germany', 'holland', 'iceland', 'ireland'];
const value = ref(null);
const items = ref(itemsArr);

const form = useForm({
    title: props.post.title,
    content: props.post.content
});

const categories = useTemplateRef('categories');

const search = (e) => {
    const request = new XMLHttpRequest();
    request.open("GET", '/categories/search/' + encodeURI(e.query.toLowerCase()), false); // `false` makes the request synchronous
    request.send(null);

    if (request.status === 200) {
        items.value = JSON.parse(request.responseText);
    }
};

const itemsIncludes = () => {
    const inputValue = document.getElementsByClassName('categories-autocomplete')[0]
        .getElementsByTagName('input')[0]
        .value;

    return itemsArr.includes(inputValue.toLowerCase());
};

const addCategory = () => {
    const categoriesInput = document.getElementById('categoriesInput');
    const inputValueLower = categoriesInput.value.toLowerCase();

    axios.post('/categories/', {
            name: inputValueLower,
        })
        .then((data) => {
            console.log(data);
        })
        .catch((data) => {
            console.log(data)
        });

    if (!itemsArr.includes(inputValueLower)) {
        itemsArr.push(inputValueLower);
        itemsArr.sort();
    }

    items.value = itemsArr;

    let valueArr = value.value ?? [];

    if (!valueArr.includes(inputValueLower)) {
        valueArr.push(inputValueLower);
    }

    value.value = valueArr;

    categories.value.overlayVisible = false;
    categoriesInput.value = '';
}
</script>

<template>
    <Head title="Editing Post" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Editing Post
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div>
                        <section>
                            <form @submit.prevent="form.patch(route('posts.update', {post: post.id}))" class="space-y-6">
                                <input type="hidden" name="_token" :value="csrf_token">

                                <div class="categories-autocomplete">
                                    <InputLabel for="categories" value="Categories" />
                                    <AutoComplete v-model="value" multiple :suggestions="items" @complete="search" ref="categories" inputId="categoriesInput"
                                                  class="block w-full">
                                        <template #footer>
                                            <div class="px-3 py-3">
                                                <PrimaryButton :v-show="itemsIncludes" @click.prevent="addCategory">&#128930; Add New</PrimaryButton>
                                            </div>
                                        </template>
                                    </AutoComplete>
                                    <InputError class="mt-2" :message="form.errors.categories" />
                                </div>

                                <div>
                                    <InputLabel for="title" value="Title" />
                                    <TextInput
                                        id="title"
                                        ref="titleInput"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.title"
                                        required
                                        autocomplete="title"
                                        autofocus
                                    />
                                    <InputError class="mt-2" :message="form.errors.title" />
                                </div>

                                <div>
                                    <InputLabel for="content" value="Content" />
                                    <textarea
                                        id="content"
                                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1 block w-full"
                                        v-model="form.content"
                                        required
                                        autocomplete="content"
                                        rows="7"
                                    ></textarea>
                                    <InputError class="mt-2" :message="form.errors.content" />
                                </div>

                                <div class="flex items-center gap-4">
                                    <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                                    <Transition
                                        enter-active-class="transition ease-in-out"
                                        enter-from-class="opacity-0"
                                        leave-active-class="transition ease-in-out"
                                        leave-to-class="opacity-0"
                                    >
                                        <p
                                            v-if="form.recentlySuccessful"
                                            class="text-sm text-gray-600"
                                        >
                                            Saved.
                                        </p>
                                    </Transition>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>

#categoriesInput {
    @apply my-1;
    @apply mx-1;
    @apply block;
    @apply w-full;
    @apply rounded-md;
    @apply border-gray-300;
    @apply shadow-sm;
    @apply focus:border-indigo-500;
    @apply focus:ring-indigo-500;
}
</style>
