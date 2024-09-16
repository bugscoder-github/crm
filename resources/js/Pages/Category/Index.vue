<template>
    <Head title="Dashboard" />

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
                        <li v-for="x in options">
                            <Link :href="route('category.edit', x.id)">Edit</Link>&nbsp;|&nbsp;
                            <span v-html="x.name"></span>
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
import { computed } from 'vue';

// const props = defineProps();
const props = defineProps(['result']);
const generateOptions = (categories, depth = 0) => {
    const options = [];

    categories.forEach(category => {
        let indent = '-'.repeat(depth * 2);
        if (indent != "") { indent += " "; }

        options.push({
            id: category.id,
            name: indent+category.name
        });

        if (category.subcategories.length > 0) {
            options.push(...generateOptions(category.subcategories, depth + 1));
        }
    });

return options;
};

// const options = computed(() => {
//     const opt = generateOptions(props.categories);
//     return opt;
// });
const options = computed(() => generateOptions(props.result));
</script>