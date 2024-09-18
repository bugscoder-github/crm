<template>
    <Head title="Location" />

    <AuthenticatedLayout>
        <template #header>
        	<template v-if="!props.form.id">New Location</template>
        	<template v-else>
        		Edit Location
        	</template>
        </template>
        <template #back>
        	<a :href="route('location.index')">Location List</a>
        </template>
		<div class="alert alert-success alert-dismissible" v-if="props.success">
			{{props.success}}
			[ <a :href="route('location.index')">Go to location list</a> ]
		</div>
        <!-- {{ form }} -->

        <div class="card">
			<div class="card-header">
			</div>
			<form @submit.prevent="handleSubmit">
				<div class="card-body">
					<div class="form-group row">
						<label for="name" class="col-sm-2 col-form-label">Name</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="name" placeholder="Name" v-model="form.name">
							<span class="text-danger" v-if="form.errors.name">{{ form.errors.name }}</span>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<button type="submit" class="btn btn-info">
						<template v-if="!props.form.id">Create</template>
						<template v-else>Update</template>
					</button>
				</div>
			</form>
		</div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head } from "@inertiajs/vue3";

// const props = defineProps();
const props = defineProps(['form', 'success']);
const form = useForm({
    ...props['form']
});

const handleSubmit = () => {
	if (form.id) {
		form.put(route("location.update", form.id));
	} else {
		form.post(route("location.store"));
	}
};
</script>
