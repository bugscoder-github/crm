<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
        	<template v-if="!props.form.id">New Category</template>
        	<template v-else>
        		Edit Category
        	</template>
        </template>
        <template #back>
        	<a :href="route('category.index')">Category List</a>
        </template>
		<div class="alert alert-success alert-dismissible" v-if="props.success">
			{{props.success}}
			<template v-if="isAdmin($page) || isOwner($page)">
				[ <a :href="route('category.index')">Go to categories list</a> ]
			</template>
		</div>
        <!-- {{ props.categories }} -->

        <div class="card">
			<div class="card-header">
				<template v-if="props.user?.name">
					<b>{{props.user?.name}}</b> - {{props.user?.email}}
				</template>
			</div>
			<form @submit.prevent="handleSubmit">
				<div class="card-body">
                    <div class="form-group row" v-if="props.categories.length">
						<label for="name" class="col-sm-2 col-form-label">Parent</label>
						<div class="col-sm-10">
							<select v-model="form.parent_id" class="form-control">
                                <option v-for="x in props.categories" :key="x.id" :value="x.id">
                                    <span v-html="x.name"></span>
                                </option>
                            </select>
							<span class="text-danger" v-if="form.errors.name">{{ form.errors.name }}</span>
						</div>
					</div>
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
						<template v-if="!props.form.id">Create</template>
						<template v-else>Update</template>
					</button>
				</div>
			</form>
		</div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head } from "@inertiajs/vue3";

// const props = defineProps();
const props = defineProps(['form', 'categories', 'success']);
const form = useForm({
    ...props['form']
});

const handleSubmit = () => {
	if (form.id) {
		form.put(route("category.update", form.id));
	} else {
		form.post(route("category.store"));
	}
};
</script>
