<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm, Head } from '@inertiajs/vue3';

const props = defineProps(['user', 'roles', 'success']);
const form = useForm({
	name: props.user?.name || '',
	email: props.user?.email || '',
	role: props.user?.roles?.id || '',
	password: '',
	password_confirmation: ''
});

const handleSubmit = () => {
	if (props.user.id) {
		form.put(route('user.update', props.user.id));
	} else {
		form.post(route('user.store'));
	}
};
</script>

<template>
    <Head title="User" />

    <AuthenticatedLayout>
        <template #header>
        	<template v-if="!props.user.id">New User</template>
        	<template v-else>
        		Edit 
        		<template v-if="isAdmin($page)">User</template>
        		<template v-else>Profile</template>
        	</template>
        </template>
        <template #back v-if="isAdmin($page)">
        	<a :href="route('user.index')">User List</a>
        </template>
		<div class="alert alert-success alert-dismissible" v-if="props.success">
			{{props.success}}
			<template v-if="isAdmin($page)">
				[ <a :href="route('user.index')">Go to users list</a> ]
			</template>
		</div>

		<div class="card card-info">
			<div class="card-header">
				<template v-if="props.user?.name">
					<b>{{props.user?.name}}</b> - {{props.user?.email}}
				</template>
			</div>
			<form class="form-horizontal" @submit.prevent="handleSubmit">
				<div class="card-body">
					<div class="form-group row">
						<label for="name" class="col-sm-2 col-form-label">Name</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="name" placeholder="Name" v-model="form.name">
							<span class="text-danger" v-if="form.errors.name">{{ form.errors.name }}</span>
						</div>
					</div>
					<div class="form-group row">
						<label for="email" class="col-sm-2 col-form-label">Email</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="email" placeholder="Email" v-model="form.email">
							<span class="text-danger" v-if="form.errors.email">{{ form.errors.email }}</span>
						</div>
					</div>
					<div class="form-group row">
						<label for="password" class="col-sm-2 col-form-label">Password</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" id="password" placeholder="Password" v-model="form.password">
							<span class="text-danger" v-if="form.errors.password">{{ form.errors.password }}</span>
						</div>
					</div>
					<div class="form-group row">
						<label for="password_confirmation" class="col-sm-2 col-form-label">Confirm Password</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" id="password_confirmation" placeholder="Confirm Password" v-model="form.password_confirmation">
							<span class="text-danger" v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</span>
						</div>
					</div>

						<div class="form-group row">
		                  <label for="exampleSelectRounded0" class="col-sm-2 col-form-label">Role</label>
							<div class="col-sm-10">
								<template v-if="isAdmin($page) && !isMine($page, props.user.id)">
			                  		<select class="custom-select" id="exampleSelectRounded0" v-model="form.role">
			                  			<option value="">Select Role</option>
	                        			<option :value="x.id" v-for="x in roles">{{ x.name }}</option>
			                  		</select>
		            			</template>
		            			<template v-else>
									{{$page.props.auth.user.role_names[0]}}
		            			</template>
		            			<span class="text-danger" v-if="form.errors.role">{{ form.errors.role }}</span>
			              </div>
		            	</div>

				</div>
				<div class="card-footer">
					<button type="submit" class="btn btn-info">
						<template v-if="!props.user.id">Create</template>
						<template v-else>Update</template>
					</button>
				</div>
			</form>
		</div>
    </AuthenticatedLayout>
</template>