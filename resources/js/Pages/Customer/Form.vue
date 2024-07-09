<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm, Head } from '@inertiajs/vue3';

const props = defineProps(['customer', 'success']);
const form = useForm({
	customer_name: props.customer?.customer_name ?? '',
	customer_phone: props.customer?.customer_phone ?? '',
});

const handleSubmit = () => {
	if (props.customer.customer_id) {
		form.put(route('customer.update', props.customer.customer_id));
	} else {
		form.post(route('customer.store'));
	}
};
</script>

<template>
    <Head title="Members" />

    <AuthenticatedLayout>
        <template #header>New Customer</template>

{{props.success}}

          <form @submit.prevent="handleSubmit">
              <div>
                  <label for="customer_name">Name</label>
                  <input type="text" id="customer_name" v-model="form.customer_name">
                  <span v-if="form.errors.customer_name">{{ form.errors.customer_name }}</span>
              </div>
              <div>
                  <label for="customer_phone">Phone</label>
                  <input type="customer_phone" id="customer_phone" v-model="form.customer_phone">
                  <span v-if="form.errors.customer_phone">{{ form.errors.customer_phone }}</span>
              </div>
              <button type="submit">Save</button>
          </form>
    </AuthenticatedLayout>
</template>