<template>
    <Head title="Dashboard" />

    <!-- {{ props.result }} -->

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in!
<br><br>
                    <ul>
                        <li v-for="x in result">
                            <Link :href="route('productService.edit', x.id)">Edit</Link>&nbsp;|&nbsp;
                            <span v-html="x.name"></span>&nbsp;
                            <span v-html="x.type"></span>
                        </li>
                    </ul>

                </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from "@inertiajs/vue3";

// const props = defineProps();
const props = defineProps(['result']);
</script>