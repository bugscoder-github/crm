<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head } from '@inertiajs/vue3';

const props = defineProps(['users']);

function getRoleClass(roleName) {
	var className = 'badge';
	if (roleName.toLowerCase() == "admin")   { className += ' bg-danger'; }
	if (roleName.toLowerCase() == "sales")   { className += ' bg-success'; }
	if (roleName.toLowerCase() == "service") { className += ' bg-warning'; }

	return className;
}
</script>

<template>
    <Head title="User List" />
 
    <AuthenticatedLayout>
        <template #header>Users List</template>

		<div class="card">
			<div class="card-header">
				<Link :href="route('user.create')" class="btn btn-primary">New</Link>
				<!-- <div class="card-tools">
					<div class="input-group input-group-sm" style="width: 150px;">
						<input type="text" name="table_search" class="form-control float-right" placeholder="Search">

						<div class="input-group-append">
							<button type="submit" class="btn btn-default">
								<i class="fas fa-search"></i>
							</button>
						</div>
					</div>
				</div> -->
			</div>
			<div class="card-body table-responsive p-0">
				<table class="table table-hover text-nowrap">
					<thead>
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>Role</th>
							<th>Created</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>

						<tr v-for="x in users" :key="x.id">
							<td>{{ x.name }}</td>
							<td>{{ x.email }}</td>
							<td>
								<template v-for="y in x.role_names">
									<div v-if="y" :class="getRoleClass(y)">{{ y }}</div>&nbsp;
								</template>
							</td>
							<td>{{ TimeToString(x.created_at) }}</td>
							<td>
								<Link :href="route('user.edit', x.id)">Edit</Link>&nbsp;
								<Link v-if="!isMine($page, x.id) || !isAdmin($page)" class="del_btn" method="delete" as="button" onclick="return confirm('Are you sure?')" :href="route('user.destroy', x.id)">Delete</Link>
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
</script>