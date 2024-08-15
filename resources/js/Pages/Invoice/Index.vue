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
                        <tr v-for="x in invoice" :key="x.invoice_id">
                            <td>
                                <a :href="route('invoice.edit', x.invoice_id)">{{ x.invoice_id }}</a>
                                <template v-if="x.quotation_id"><br><small><a :href="route('quotation.edit', x.quotation_id)" target="_blank">(Quote: {{ x.quotation_id }})</a></small></template>
                            </td>
                            <td>
                                <template v-if="!x.invoice_company">{{ x.invoice_name }}</template>
                                <template v-else>
                                    {{ x.invoice_company }}<br>
                                    (Attn: {{ x.invoice_name }})
                                </template>
                                

                            </td>
                            <td>
                                {{ amountFormat(x.invoice_grandTotal) }}
                                <br><small>{{ amountFormat(x.invoice_total) }} ({{ amountFormat(x.invoice_sst) }} @ {{ x.invoice_sstPct }}%)</small>
                            </td>
                            <td>{{ TimeToString(x.created_at) }}</td>
                            <td><a :href="route('invoice.edit', x.invoice_id)">Edit</a></td>
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