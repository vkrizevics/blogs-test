<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import AutoComplete from 'primevue/autocomplete';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {nextTick, ref} from "vue";

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

const editingCategories = ref(false);
const categoryNameInput = ref(null);

let itemsArr = ['Germany', 'Holland', 'Iceland', 'Ireland'];
const value = ref(null);
const items = ref(itemsArr);

const form = useForm({
    title: props.post.title,
    content: props.post.content
});

const formCategory = useForm({
    name: ''
});

const editCategories = () => {
    editingCategories.value = true;

    nextTick(() => categoryNameInput.value.focus());
};

const closeModal = () => {
    editingCategories.value = false;

    formCategory.clearErrors();
    formCategory.reset();
};

const search = (e) => {
    axios.get('/categories')
        .then((ee) => {
            items.value = itemsArr.filter((country) => {
                return country.toLowerCase().startsWith(e.query.toLowerCase())
            });
        })
        .catch((ee) => {
            items.value = itemsArr.filter((country) => {
                return country.toLowerCase().startsWith(e.query.toLowerCase())
            });
        });
};
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

                                <AutoComplete v-model="value" multiple :suggestions="items" @complete="search"
                                              class="mt-1 block w-full">
                                    <template #chip>

                                    </template>
                                </AutoComplete>

                                <div>
                                    <InputLabel for="title" value="Title" />
                                    <TextInput
                                        id="title"
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

                                    <PrimaryButton @click.prevent="editCategories">Categories</PrimaryButton>
                                </div>
                            </form>

                            <Modal :show="editingCategories" @close="closeModal">
                                <div class="p-6">
                                    <div class="mt-6">
                                        <InputLabel for="name" value="Category" />
                                        <TextInput
                                            id="name"
                                            ref="categoryNameInput"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="formCategory.name"
                                            required
                                            autocomplete="category"
                                            autofocus
                                        />

                                        <InputError :message="formCategory.errors.name" class="mt-2" />
                                    </div>

                                    <div class="mt-6 flex justify-end">
                                        <!--<SecondaryButton @click="closeModal">
                                            Cancel
                                        </SecondaryButton>

                                        <DangerButton
                                            class="ms-3"
                                            :class="{ 'opacity-25': form.processing }"
                                            :disabled="form.processing"
                                            @click="deleteUser"
                                        >
                                            Delete Account
                                        </DangerButton>-->
                                    </div>
                                </div>
                            </Modal>

                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>

.p-autocomplete-input-chip input[type="text"] {
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
/**
 *:mt-1 *:block *:w-full *:rounded-md *:border-gray-300 *:shadow-sm focus:*:border-indigo-500 focus:*:ring-indigo-500
 */
</style>
