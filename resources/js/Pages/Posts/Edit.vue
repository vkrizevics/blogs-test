<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import PostsLayout from "@/Layouts/PostsLayout.vue";
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
    auth_user: {
        type: Boolean,
        required: true
    },
    categories: {
        type: Array,
        required: true
    }
});

let categoriesFoundArr = [];
const categoriesSelected = ref(props.categories);
const categoriesFound = ref(categoriesFoundArr);

const form = useForm({
    title: props.post.title,
    content: props.post.content
});

const categories = useTemplateRef('categories');

const searchCategories = (e) => {
    const request = new XMLHttpRequest();
    request.open("GET", '/categories/search/' + encodeURI(e.query.toLowerCase()), false);
    request.send(null);

    if (request.status === 200) {
        categoriesFoundArr = JSON.parse(request.responseText);
        categoriesFound.value = categoriesFoundArr;
    }
};

const categoriesIncludes = () => {
    const inputValue = document.getElementById('categoriesInput').value;

    return categoriesFoundArr.includes(inputValue.toLowerCase());
};

const addCategory = () => {
    const categoriesInput = document.getElementById('categoriesInput');
    const inputValueLower = categoriesInput.value.toLowerCase();

    axios.post('/categories/', {
            name: inputValueLower,
        })

        .then((data) => {})
        .catch((data) => {
            // Validation failed or something else happened
            form.errors.categories = data.response.data.errors.name.join('<br>');
        });

    if (!categoriesFoundArr.includes(inputValueLower)) {
        categoriesFoundArr.push(inputValueLower);
        categoriesFoundArr.sort();
    }

    categoriesFound.value = categoriesFoundArr;

    let categoriesArr = categoriesSelected.value ?? [];

    if (!categoriesArr.includes(inputValueLower)) {
        categoriesArr.push(inputValueLower);
    }

    categoriesSelected.value = categoriesArr;

    categories.value.overlayVisible = false;
    categoriesInput.value = '';
}

const formPatch = () => {
    form.transform((data) => ({
            ...data,
            categories: categoriesSelected.value
        }))
        .patch(
            route('posts.update', { post: props.post.id })
        );
}
</script>

<template>
    <Head title="Editing Post" />

    <PostsLayout>
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
                            <form @submit.prevent="formPatch" class="space-y-6">
                                <div class="categories-autocomplete">
                                    <InputLabel for="categories" value="Categories" class=""/>
                                    <AutoComplete v-model="categoriesSelected" multiple :suggestions="categoriesFound" @complete="searchCategories"
                                                  ref="categories" inputId="categoriesInput" class="w-full"
                                                  :inputClass="['px-3', '-my-0.5', 'block', 'w-full', 'rounded-md', 'border-gray-300', 'shadow-sm',
                                                                'focus:border-indigo-500', 'focus:ring-indigo-500']">
                                    <template #footer>
                                            <div class="px-3 py-3">
                                                <PrimaryButton :v-show="categoriesIncludes" @click.prevent="addCategory">&#128930; Add New</PrimaryButton>
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
                                    <InputLabel for="content" value="Text" />
                                    <textarea
                                        id="content"
                                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1 block w-full"
                                        v-model="form.content"
                                        required
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
    </PostsLayout>
</template>

<style>

.p-autocomplete-input-chip {
    @apply mx-px;
}
</style>
