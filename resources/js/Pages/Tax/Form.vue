<template>
    <Head title="Tax" />

    <AuthenticatedLayout>
		<template #header v-if="form.id">{{ form.name }}</template>
		<template #header v-else>{{ $t('New Tax') }}</template>

		<div class="alert alert-success alert-dismissible" v-if="success">
			{{success}}
		</div>

		<div class="card">
			<div class="card-header">
				{{ $t("Tax Information") }}
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
								<label for="currency" class="col-sm-2 col-form-label">{{ $t('Currency') }}</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="currency" :placeholder="$t('Currency')" v-model="form.currency">
									<span class="text-danger" v-if="errors.currency">{{ errors.currency }}</span>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group row">
								<label for="tax_type" class="col-sm-2 col-form-label">{{ $t('Tax Type') }}</label>
								<div class="col-sm-10">
									<select class="form-control" id="tax_type" :placeholder="$t('Tax Type')" v-model="form.tax_type">
										<option value="exclusive">{{ $t('Exclusive') }}</option>
										<option value="inclusive">{{ $t('Inclusive') }}</option>
									</select>
									<span class="text-danger" v-if="errors.tax_type">{{ errors.tax_type }}</span>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group row">
								<label for="charge_type" class="col-sm-2 col-form-label">{{ $t('Charge Type') }}</label>
								<div class="col-sm-10">
									<select class="form-control" id="charge_type" :placeholder="$t('Charge Type')" v-model="form.charge_type">
										<option value="percentage">{{ $t('Percentage') }}</option>
										<option value="amount">{{ $t('Exact Amount') }}</option>
									</select>
									<span class="text-danger" v-if="errors.charge_type">{{ errors.charge_type }}</span>
								</div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group row">
								<label for="amount" class="col-sm-1 col-form-label">{{ $t('Amount') }}</label>
								<div class="col-sm-11">
									<input type="text" class="form-control" id="amount" :placeholder="$t('Amount')" v-model="form.amount">
									<span class="text-danger" v-if="errors.amount">{{ errors.amount }}</span>
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
				useForm(this.form).put(route('tax.update', { id: this.form.id }), {
				});
			} else {
				useForm(this.form).post(route('tax.store'), {
				});
			};
		},
    }
}

</script>