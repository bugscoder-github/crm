<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        {{ props.category }}

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    
                    <form @submit.prevent="handleSubmit">
                        <template v-if="props.category.length">
                        Category: <select v-model="form.category_id">
                             <option v-for="x in generateOptions(props.category)" :key="x.id" :value="x.id">
                                <span v-html="x.name"></span>
                             </option>
                        </select><br><br></template>
                        Type: <select v-model="form.type">
                            <option value="Product">Product</option>
                            <option value="Service">Service</option>
                        </select><br>
                        Name: <input v-model="form.name">
                        <span class="text-danger" v-if="form.errors.name" >{{ form.errors.name }}</span >
                            <br><br>
                        <button type="submit" class="btn btn-info">
										<template v-if="!form.id">Create</template>
										<template v-else>Update</template>
									</button>
                    </form>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head } from "@inertiajs/vue3";

// const props = defineProps();
const props = defineProps(['form', 'category']);
const form = useForm({
    ...props['form']
});

const handleSubmit = () => {
	if (form.id) {
		form.put(route("productService.update", form.id));
	} else {
		form.post(route("productService.store"));
	}
};

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
</script>
