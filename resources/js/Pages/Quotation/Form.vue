<script setup>
import { onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, useForm, Head } from "@inertiajs/vue3";

const props = defineProps(['lead']);
const form = useForm({
	lead_id: props.lead?.lead_id ?? 0,
	quotation_name: props.lead?.lead_name ?? '',
	quotation_phone: props.lead?.lead_phone ?? ''
});

const handleSubmit = () => {
	form.post(route('quotation.store'));
};
</script>
<template>
	<Head title="Members" />

	<AuthenticatedLayout>
		<template #header>
			New Quotation
		</template>

		{{props.lead}}

		<section class="content">
			<div class="container-fluid">
				<form @submit.prevent="handleSubmit">
					<input id="lead_id" v-model="form.lead_id">
					<input id="lead_name" v-model="form.quotation_name">
					<input id="lead_phone" v-model="form.quotation_phone">
					<button type="submit" class="btn btn-info">
						Create
					</button>
				</form>
			</div>
		</section>
	</AuthenticatedLayout>
</template>