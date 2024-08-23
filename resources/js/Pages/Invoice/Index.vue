<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, Head } from "@inertiajs/vue3";

const props = defineProps(["invoice"]);
</script>

<template>
    <Head title="Invoice List" />

    <AuthenticatedLayout>
        <template #header>Invoice List</template>

        <div class="card">
            <div class="card-header">
                <Link :href="route('invoice.create')" class="btn btn-primary mr-3">New</Link>
            </div>
            <div class="card-body table-responsive p-0">
                
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Created</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="x in invoice" :key="x.id">
                            <td>
                                <a :href="route('invoice.edit', x.id)">{{ x.invoice_number }}</a>
                                <template v-if="x.quotation_id"><br><small><a :href="route('quotation.edit', x.quotation_id)" target="_blank">(Quote: {{ x.quotation_id }})</a></small></template>
                            </td>
                            <td>
                                <template v-if="!x.company">{{ x.invoice_number }}</template>
                                <template v-else>
                                    {{ x.company }}<br>
                                    (Attn: {{ x.customer_name }})
                                </template>
                                

                            </td>
                            <td>
                                {{ x.currency }} {{ x.total_amount }}
                            </td>
                            <td>{{ TimeToString(x.created_at) }}</td>
                            <td><a :href="route('invoice.edit', x.id)">Edit</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                <!-- Another Comment -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>