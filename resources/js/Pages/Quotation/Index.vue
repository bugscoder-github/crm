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
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Created</th>
                            <th>Edit</th>
                            <th>PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="x in quotation" :key="x.quotation_id">
                            <td>{{ x.quotation_name }}</td>
                            <td>
                                {{ x.quotation_grandTotal }}
                                <br><small>{{ x.quotation_total }} ({{ x.quotation_sst }})</small>
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