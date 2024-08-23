<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, Head } from "@inertiajs/vue3";

const props = defineProps(["leads"]);

const countNew  = props.leads.filter((x) => x.lead_status == 1).length;
const countPen  = props.leads.filter((x) => x.lead_status == 2).length;
const countWIP  = props.leads.filter((x) => x.lead_status == 3).length;
const countDone = props.leads.filter((x) => x.lead_status == 4).length;

function doneClass(x = null) {
	if (x == 4) { return 'text-muted'; }
}
</script>

<template>
    <Head title="Leads List" />

    <AuthenticatedLayout>
        <template #header>Leads List</template>

        <!-- {{ leads }} -->

        <div class="card">
            <div class="card-header">
                <Link :href="route('lead.create')" class="btn btn-primary mr-3">New</Link>
                <b> {{ countNew }}</b> new out of {{ leads.length }} records.
                	{{ countPen }} pending. {{ countWIP }} in progress. {{ countDone }} done.
                <!-- New lead since last load. Click here to refresh. -->
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
            <div class="card-body">
                <table class="table table-hover text-nowrap" v-if="leads.length">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Assigned</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="x in leads" :key="x.lead_id" :class="doneClass(x.lead_status)">
                            <td>{{ x.lead_name }}</td>
                            <td>{{ x.lead_phone }}</td>
                            <td>{{ x.lead_location }}</td>
                            <td>
                            	<template v-if="x.user?.name">
                                    {{ x.user.name }}
                                    <small v-if="isOwner($page) && x.user.teams.name"><br>{{ x.user.teams.name }}</small>
                                </template>

                             	<template v-else>-</template>
                            </td>
                            <td>
                                <div class="badge bg-danger"  v-if="x.lead_status == 1">New</div>
                                <div class="badge bg-warning" v-if="x.lead_status == 2">Pending</div>
                                <div class="badge bg-success" v-if="x.lead_status == 3">WIP</div>
                                <div class="badge bg-gray"    v-if="x.lead_status == 4">Done</div>
                                <br><small v-if="x.quotation"><Link :href="route('quotation.edit', x.quotation.id)">{{x.quotation.quotation_number}}</Link></small>
                            </td>
                            <td>
                            	{{ TimeToString(x.lead_createdAt) }}
                             	<br><small>{{x.lead_source}}</small>
                            </td>
                            <td>
                                <a :href="route('lead.edit', x.lead_id)">Edit</a>&nbsp;
                                <Link v-if="x.lead_status != 4 && isAdmin($page)" class="del_btn" method="delete" as="button" onclick="return confirm('Are you sure?')" :href="route('lead.destroy', x.lead_id)">Delete</Link>
                                <Link v-if="x.lead_status != 4" class="text-muted del_btn" method="post" as="button" onclick="return confirm('Are you sure?')" :href="route('lead.done', x.lead_id)">Done</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="row col-md-12" v-else>Leads not found. <Link :href="route('lead.create')" class="ml-1">Create new</Link>.</div>
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