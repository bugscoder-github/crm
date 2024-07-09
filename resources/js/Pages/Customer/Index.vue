<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head } from '@inertiajs/vue3';

const props = defineProps(['customers']);
</script>

<template>
    <Head title="Customers" />
 
    <AuthenticatedLayout>

        <template #header>Customers List</template>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>

                <tr v-for="x in customers" :key="x.customer_id">
                    <td>{{ x.customer_name }}</td>
                    <td>{{ x.customer_phone }}</td>
                    <td>
                        <Link :href="route('customer.edit', x.customer_id)">Edit</Link>
                        <Link method="delete" as="button" onclick="return confirm('Are you sure?')" :href="route('customer.destroy', x.customer_id)">Delete</Link>
                    </td>
                </tr>
                  </tbody>
                </table>
              </div>

        
    </AuthenticatedLayout>
</template>

<script setup>
</script>