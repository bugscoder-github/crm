<template>
    <Head title="Product Service In Out" />

    <AuthenticatedLayout>
        <template #header>
        	<template v-if="!props.form.id">New Product/Service In/Out</template>
        	<template v-else>
        		Edit Product/Service In/Out
        	</template>
        </template>
        <template #back>
        	<a :href="route('productServiceInOut.index')">Product/Service In/Out List</a>
        </template>
		<div class="alert alert-success alert-dismissible" v-if="props.success">
			{{props.success}}
			[ <a :href="route('productServiceInOut.index')">Go to product/service list</a> ]
		</div>

        <!-- {{ props.products }} -->

        <div class="card">
			<div class="card-header">
			</div>
			<form @submit.prevent="handleSubmit">
				<div class="card-body">
                    <div class="form-group row">
						<label for="name" class="col-sm-2 col-form-label">In/Out</label>
						<div class="col-sm-10">
                            <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" v-model="form.type" value="In">
                          <label class="form-check-label">In</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" v-model="form.type" value="Out">
                          <label class="form-check-label">Out</label>
                        </div>
                      </div>
                        </div>
					</div>
                    
                    <div class="form-group row">
						<label for="name" class="col-sm-2 col-form-label">Item Name</label>
						<div class="col-sm-10">
							<select v-model="form.product_service_id">
                                <option v-for="x in props.products" :key="x.id" :value="x.id">{{ x.name }}</option>
                            </select>
							<span class="text-danger" v-if="form.errors.type">{{ form.errors.type }}</span>
						</div>
					</div>
                    <div class="form-group row">
						<label for="qty" class="col-sm-2 col-form-label">Quantity</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="qty" placeholder="Quantity" v-model="form.qty">
							<span class="text-danger" v-if="form.errors.qty">{{ form.errors.qty }}</span>
						</div>
					</div>
					<div class="form-group row">
						<label for="unitPrice" class="col-sm-2 col-form-label">Unit Price</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="unitPrice" placeholder="Name" v-model="form.unitPrice">
							<span class="text-danger" v-if="form.errors.unitPrice">{{ form.errors.unitPrice }}</span>
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
const props = defineProps([
    'form', 
    'products',
    'success'
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
