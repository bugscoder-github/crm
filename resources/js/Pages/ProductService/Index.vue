<template>
    <Head title="Dashboard" />

    <!-- {{ props.result }} -->

    <AuthenticatedLayout>
        <template #header>Product/Service</template>

        <div class="card">
			<div class="card-header">
				<Link :href="route('product.create')" class="btn btn-primary">New</Link>
			</div>
			<div class="card-body table-responsive p-0">
				<table class="table table-hover text-nowrap">
					<thead>
						<tr>
							<th>Name</th>
                            <th>Type</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>

						<tr v-for="x in result" :key="x.id">
							<td>{{ x.name }}</td>
                            <td>{{ x.type }}</td>
							<td>
								<Link :href="route('product.edit', x.id)">Edit</Link>&nbsp;
								<Link class="del_btn" method="delete" as="button" onclick="return confirm('Are you sure?')" :href="route('product.destroy', x.id)">Delete</Link>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
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

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from "@inertiajs/vue3";

// const props = defineProps();
const props = defineProps(['result']);
</script>