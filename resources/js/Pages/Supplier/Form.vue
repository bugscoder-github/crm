<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        {{ form }}

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    
                    <form @submit.prevent="handleSubmit">
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
const props = defineProps(['form']);
const form = useForm({
    ...props['form']
});

const handleSubmit = () => {
	if (form.id) {
		form.put(route("supplier.update", form.id));
	} else {
		form.post(route("supplier.store"));
	}
};
</script>
