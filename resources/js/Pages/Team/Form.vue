<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm, Head } from '@inertiajs/vue3';

const props = defineProps(['team', 'success']);
const form = useForm({
	name: props.team?.name || '',
});

const handleSubmit = () => {
	if (props.team.id) {
		form.put(route('team.update', props.team.id));
	} else {
		form.post(route('team.store'));
	}
};
</script>

<template>
    <Head title="Team" />

    <AuthenticatedLayout>
        <template #header>
        	<template v-if="!props.team.id">New Team</template>
        	<template v-else>
        		Edit 
        		<template v-if="isAdmin($page)">Team</template>
        		<template v-else>Profile</template>
        	</template>
        </template>
        <template #back v-if="isAdmin($page)">
        	<a :href="route('team.index')">Team List</a>
        </template>
		<div class="alert alert-success alert-dismissible" v-if="props.success">
			{{props.success}}
			<template v-if="isAdmin($page)">
				[ <a :href="route('team.index')">Go to teams list</a> ]
			</template>
		</div>

		<div class="card">
			<div class="card-header">
				
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
				</div>
				<div class="card-footer">
					<button type="submit" class="btn btn-info">
						<template v-if="!props.team.id">Create</template>
						<template v-else>Update</template>
					</button>
				</div>
			</form>
		</div>
    </AuthenticatedLayout>
</template>