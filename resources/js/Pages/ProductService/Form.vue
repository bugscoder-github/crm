<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
        	<template v-if="!props.form.id">New Product/Service</template>
        	<template v-else>
        		Edit Product/Service
        	</template>
        </template>
        <template #back>
        	<a :href="route('productService.index')">Product/Service List</a>
        </template>
		<div class="alert alert-success alert-dismissible" v-if="props.success">
			{{props.success}}
			[ <a :href="route('productService.index')">Go to product/service list</a> ]
		</div>

        <!-- {{ props.category }} -->

        <div class="card">
			<div class="card-header">
			</div>
			<form @submit.prevent="handleSubmit">
				<div class="card-body">
                    <div class="form-group row">
						<label for="name" class="col-sm-2 col-form-label">Type</label>
						<div class="col-sm-10">
							<select v-model="form.type" class="form-control">
                                <option value="Product">Product</option>
                                <option value="Service">Service</option>
                            </select>
							<span class="text-danger" v-if="form.errors.type">{{ form.errors.type }}</span>
						</div>
					</div>
                    <div class="form-group row">
						<label for="name" class="col-sm-2 col-form-label">Category</label>
						<div class="col-sm-10">
							<select v-model="form.category_id" class="form-control">
                                <option v-for="x in props.category" :key="x.id" :value="x.id">
                                    <span v-html="x.name"></span>
                                </option>
                            </select>
							<span class="text-danger" v-if="form.errors.category_id">{{ form.errors.category_id }}</span>
						</div>
					</div>
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
const props = defineProps(['form', 'category', 'success']);
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
</script>
