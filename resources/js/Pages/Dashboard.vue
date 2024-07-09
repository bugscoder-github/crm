<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<style>
ul {
	margin :0;
	padding-left: 15px;
}
ul li.ok {
	text-decoration: line-through;
}
</style>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>Dashboard</template>

		{{$page.props.auth.user}}
        Pending dashboard layout
        <div>---</div>
        <div>TODO</div>
        <div>---</div>
        <ul>
        	<li class="">Settings</li>
        	<li>
        		Lead
        		<ul>        			
		        	<li class="">user able to accept/reject</li>
		        	<li>user able to pick job from unassigned pool</li>
		        	<li>lead created by</li>
		        	<li>hide done</li>
		        	<li>change dropdown to select2</li>
        		</ul>
        	</li>
        </ul>
    </AuthenticatedLayout>
</template>
