<script setup>
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, useForm, Head } from "@inertiajs/vue3";

const props = defineProps([]);
const form = useForm({
	metadata_type: '',
	metadata_label: '',
	metadata_value: '',
});

const handleSubmit = () => {
	form.post(route("metadata.store"));
};
</script>

<template>
	<Head title="Members" />

	<AuthenticatedLayout>
		<template #header>
		</template>
		
		<form @submit.prevent="handleSubmit" >
			Type: <select v-model="form.metadata_type">
				<option value="">Select Type</option>
				<option value="quotation">Quotation</option>
			</select><br>
			Label: <input v-model="form.metadata_label"><br>
			Value: <input v-model="form.metadata_value"><br>
			<button type="submit" class="btn btn-info">Save</button>
		</form>
		

		<!-- {{  props.log }} -->

		<section class="content">
			<div class="container-fluid">
				<div class="row">
					

					
				</div>
				
			</div>
		</section>

		
	</AuthenticatedLayout>
</template>