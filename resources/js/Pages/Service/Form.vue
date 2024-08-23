<template>
    <Head title="Service" />

    <AuthenticatedLayout>
		<template #header v-if="form.id">{{ form.name }}</template>
		<template #header v-else>{{ $t('New Service') }}</template>

		<div class="alert alert-success alert-dismissible" v-if="success">
			{{success}}
		</div>

		<div class="card">
			<div class="card-header">
				{{ $t("Service Information") }}
			</div>
			<form class="form-horizontal" @submit.prevent="handleSubmit">
				<div class="card-body">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group row">
								<label for="name" class="col-sm-2 col-form-label">{{ $t('Name') }}</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="name" :placeholder="$t('Name')" v-model="form.name">
									<span class="text-danger" v-if="errors.name">{{ errors.name }}</span>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group row">
								<label for="price" class="col-sm-2 col-form-label">{{ $t('Price') }}</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="price" :placeholder="$t('Price')" v-model="form.price">
									<span class="text-danger" v-if="errors.price">{{ errors.price }}</span>
								</div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group row">
								<label for="description" class="col-sm-1 col-form-label">{{ $t('Description') }}</label>
								<div class="col-sm-11">
									<textarea class="form-control" id="description" :placeholder="$t('Description')" v-model="form.description" />
									<span class="text-danger" v-if="errors.description">{{ errors.description }}</span>
								</div>
							</div>
						</div>
					</div>
					
				</div>
				<div class="card-footer">
					<button type="submit" class="btn btn-info">
						<template v-if="!form.id">{{ $t("Create") }}</template>
						<template v-else>{{ $t("Update") }}</template>
					</button>
				</div>
			</form>
		</div>
    </AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import FormValidation from "@/Helpers/FormValidation.vue";
import { useForm, Head } from "@inertiajs/vue3";

export default {
    props: [
		'form', 
		'success'
    ],

    mixins: [FormValidation],

    components: {
		Head,
		useForm,
		AuthenticatedLayout
    },

    data () {
        return {
           errors: {}
        }
    },
    mounted() {
        this.estimate();
    },
    methods: {
		handleSubmit() {
			if (this.form.id) {
				useForm(this.form).put(route('service.update', { id: this.form.id }), {
				});
			} else {
				useForm(this.form).post(route('service.store'), {
				});
			};
		},
    }
}

</script>