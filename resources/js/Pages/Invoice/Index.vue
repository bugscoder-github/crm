<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, Head } from "@inertiajs/vue3";

const props = defineProps(["quotation"]);
</script>

<template>
    <Head title="Leads List" />

    <AuthenticatedLayout>
        <template #header>Quotation List</template>

        <div class="card">
            <div class="card-header">
                <Link :href="route('quotation.create')" class="btn btn-primary mr-3">New</Link>
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
                            <th>PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="x in quotation" :key="x.quotation_id">
                            <td>
                                <a :href="route('quotation.edit', x.quotation_id)">{{ x.quotation_id }}</a>
                                <template v-if="x.lead_id"><br><small><a :href="route('lead.edit', x.lead_id)" target="_blank">(Lead: {{ x.lead_id }})</a></small></template>
                            </td>
                            <td>
                                <template v-if="!x.quotation_company">{{ x.quotation_name }}</template>
                                <template v-else>
                                    {{ x.quotation_company }}<br>
                                    (Attn: {{ x.quotation_name }})
                                </template>
                                

                            </td>
                            <td>
                                {{ amountFormat(x.quotation_grandTotal) }}
                                <br><small>{{ amountFormat(x.quotation_total) }} ({{ amountFormat(x.quotation_sst) }} @ {{ x.quotation_sstPct }}%)</small>
                            </td>
                            <td>{{ TimeToString(x.created_at) }}</td>
                            <td><a :href="route('quotation.edit', x.quotation_id)">Edit</a></td>
                            <td><a :href="route('quotation.pdf', x.quotation_id)">PDF</a></td>
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