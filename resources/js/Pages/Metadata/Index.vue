<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Metadata" />

    <AuthenticatedLayout>
        <template #header>Metadata</template>

        <div class="card">
            <div class="card-header">
                <Link :href="route('metadata.create')" class="btn btn-primary mr-3">New</Link>
            </div>
            <div class="card-body table-responsive p-0"></div>
            <div class="card-footer clearfix">
                <!-- <ul class="pagination pagination-sm m-0 float-right">
					<li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
					<li class="page-item"><a class="page-link" href="#">1</a></li>
					<li class="page-item"><a class="page-link" href="#">2</a></li>
					<li class="page-item"><a class="page-link" href="#">3</a></li>
					<li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
				</ul> -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>