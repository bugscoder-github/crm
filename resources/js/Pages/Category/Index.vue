<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>Category</template>

        <div class="card">
			<div class="card-header">
				<Link :href="route('category.create')" class="btn btn-primary">New</Link>
			</div>
			<div class="card-body table-responsive p-0">
				<table class="table table-hover text-nowrap">
					<thead>
						<tr>
							<th>Name</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>

						<tr v-for="x in result" :key="x.id">
							<td>{{ x.name }}</td>
							<td>
								<Link :href="route('category.edit', x.id)">Edit</Link>&nbsp;
								<Link class="del_btn" method="delete" as="button" onclick="return confirm('Are you sure?')" :href="route('category.destroy', x.id)">Delete</Link>
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
import { computed } from 'vue';

// const props = defineProps();
const props = defineProps(['result']);
// const generateOptions = (categories, depth = 0) => {
//     const options = [];

//     categories.forEach(category => {
//         let indent = '-'.repeat(depth * 2);
//         if (indent != "") { indent += " "; }

//         options.push({
//             id: category.id,
//             name: indent+category.name
//         });

//         if (category.subcategories.length > 0) {
//             options.push(...generateOptions(category.subcategories, depth + 1));
//         }
//     });

// return options;
// };

// const options = computed(() => {
//     const opt = generateOptions(props.categories);
//     return opt;
// });
// const options = computed(() => generateOptions(props.result));
</script>