<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        {{ props.products }}

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    
                    <form @submit.prevent="handleSubmit">
                        <input type="radio" v-model="form.type" value="In">In <input type="radio" v-model="form.type" value="Out">Out<br>
                        Item Name: <select v-model="form.productServices_id">
                            <option v-for="x in props.products" :key="x.id" :value="x.id">{{ x.name }}</option>
                        </select>
                        <br>
                        Quantity: <input v-model="form.qty"><br>
                        Unit Price: <input v-model="form.unitPrice"><br>
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
const props = defineProps([
    'form', 
    'products'
]);
const form = useForm({
    ...props['form']
});

const handleSubmit = () => {
	if (form.id) {
		form.put(route("productServiceInOut.update", form.id));
	} else {
		form.post(route("productServiceInOut.store"));
	}
};
</script>
