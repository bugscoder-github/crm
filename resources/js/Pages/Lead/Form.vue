<style>
#commentTemplate,
#commentList {
	margin: 0;
	padding: 0;
	list-style: none;
}
#commentTemplate li {
	margin-right: 2px;
}

.direct-chat-messages {
	/*	height: auto !important;*/
	padding: 0;
	width: 100%;
}

.direct-chat-msg {
	margin-bottom: 2px;
	background-color: #f8f9fa;
	padding: 10px;
}

.del_btn {
	margin-left: 10px;
	visibility: hidden;
}

.direct-chat-text:hover .del_btn,
.direct-chat-msg:hover .del_btn {
	visibility: visible;
}

.disableInput input {
	pointer-events: none;
}

.done_btn {
	border: 0;
	background: none;
	color: #0056b3;
	border-bottom: 1px solid #0056b3;
	padding-left: 0;
	padding-right: 0;
}
</style>

<template>
	<Head title="Members" />

	<AuthenticatedLayout>
		<template #header>
			<template v-if="!props.lead.lead_id">New Lead</template>
			<template v-else>Edit Lead</template>
		</template>
		
		<div class="alert alert-success alert-dismissible" v-if="props.success">
			{{props.success}}
			<template v-if="isAdmin($page)">
				[ <a :href="route('lead.index')">Go to leads list</a> ]
			</template>
		</div>

		<!-- {{  props.log }} -->

		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div :class="halfOrFullWidth()">
						<div class="card">
							<div class="card-header">
								Basic Information
								<div style="float: right">
									<!-- <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default" style="float: right;">Search Customer</button> -->
								</div>
								
							</div>
							<form @submit.prevent="handleSubmit" :class="disableInput()" id="basic_information">
								<input type="hidden" v-model="form.customer_id">
								<div class="card-body">
									<div class="form-group row">
										<label for="lead_enquiry" :class="autoLabelWidthClass()">Salesperson</label>
										<div :class="autoInputWidthClass()">
											<template v-if="isAdmin($page)">
												<select id="user_id" class="custom-select" v-model="form.user_id" >
													<option value="0"> Select Sales Person </option>
													<option :value="x.id" v-for="x in users" > {{ x.name }} </option>
												</select>
												<span class="text-danger" v-if="form.errors.user_id" >{{ form.errors.user_id }}</span >
											</template>
											<template v-if="isMine($page, props.lead?.user_id) || !isAdmin($page)" >
												<div class="col-form-label">{{ $page.props.auth.user.name }}</div>
												<!-- <div class="col-form-label" v-for="x in users" > {{ x.name }} </div> -->
											</template>
										</div>
									</div>
									<div class="form-group row">
										<label for="lead_companyName" :class="autoLabelWidthClass()">Company Name</label >
										<div :class="autoInputWidthClass()">
											<input type="text" class="form-control" id="lead_companyName" placeholder="Company Name" v-model="form.lead_companyName" />
											<span class="text-danger" v-if="form.errors.lead_companyName" >{{ form.errors.lead_companyName }}</span >
										</div>
									</div>
									<div class="form-group row">
										<label for="lead_name" class="col-sm-2 col-form-label" >Name</label >
										<div class="col-sm-10">
											<input type="text" class="form-control" id="lead_name" placeholder="Name" v-model="form.lead_name" />
											<span class="text-danger" v-if="form.errors.lead_name" >{{ form.errors.lead_name }}</span >
										</div>
									</div>
									<div class="form-group row">
										<label for="lead_phone" class="col-sm-2 col-form-label" >Phone</label >
										<div class="col-sm-10">
											<input type="text" class="form-control" id="lead_phone" placeholder="Phone" v-model="form.lead_phone" />
											<div data-toggle="modal" data-target="#modal-default" style="cursor: pointer;">Search Customer</div>
											<span class="text-danger" v-if="form.errors.lead_phone" >{{ form.errors.lead_phone }}</span >
										</div>
									</div>
									<div class="form-group row">
										<label for="lead_email" class="col-sm-2 col-form-label" >Email</label >
										<div class="col-sm-10">
											<input type="text" class="form-control" id="lead_email" placeholder="Email" v-model="form.lead_email" />
											<span class="text-danger" v-if="form.errors.lead_email" >{{ form.errors.lead_email }}</span >
										</div>
									</div>
									<div class="form-group row">
										<label for="lead_location" class="col-sm-2 col-form-label" >Location</label >
										<div class="col-sm-10">
											<input type="text" class="form-control" id="lead_location" placeholder="Location" v-model="form.lead_location" />
											<span class="text-danger" v-if="form.errors.lead_location" >{{ form.errors.lead_location }}</span >
										</div>
									</div>
									<div class="form-group row">
										<label for="lead_enquiry" class="col-sm-2 col-form-label">Enquiry</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="lead_enquiry" placeholder="Enquiry" v-model="form.lead_enquiry" />
											<span class="text-danger" v-if="form.errors.lead_enquiry">{{ form.errors.lead_enquiry }}</span>
										</div>
									</div>
									<div class="form-group row">
										<label for="lead_source" class="col-sm-2 col-form-label" >Source</label >
										<div class="col-sm-10">
											<template v-if="form.lead_source == 'Web'" >
												<div class="col-form-label"> {{ form.lead_source }} </div>
											</template>
											<template v-else>
												<select id="lead_source" class="custom-select" v-model="form.lead_source" >
													<option value=""> Select Source </option>
													<option :value="x" v-for="x in meta[ 'sources' ]" > {{ x }} </option>
												</select>
												<span class="text-danger" v-if=" form.errors.lead_source " >{{ form.errors.lead_source }}</span >
											</template>
										</div>
									</div>
										<div class="form-group row">
										<label for="lead_premiseType" class="col-sm-2 col-form-label" >Premise</label >
										<div class="col-sm-10">
											<input type="text" class="form-control" id="lead_premiseType" placeholder="Premise" v-model="form.lead_premiseType" />
											<span class="text-danger" v-if="form.errors.lead_premiseType" >{{ form.errors.lead_premiseType }}</span >
										</div>
									</div>
									<div class="form-group row">
										<label for="lead_serviceType" class="col-sm-2 col-form-label" >Service</label >
										<div class="col-sm-10">
											<input type="text" class="form-control" id="lead_serviceType" placeholder="Service" v-model="form.lead_serviceType" />
											<span class="text-danger" v-if="form.errors.lead_serviceType" >{{ form.errors.lead_serviceType }}</span >
										</div>
									</div>
									<div class="form-group row">
										<label for="lead_remark" class="col-sm-2 col-form-label" >Remark</label >
										<div class="col-sm-10">
											<textarea class="form-control" id="lead_remark" placeholder="Remark" v-model="form.lead_remark"></textarea>
											<span class="text-danger" v-if="form.errors.lead_remark">{{ form.errors.lead_remark }}</span>
										</div>
									</div>
								</div>
								<!-- <div class="card shadow-none">
									<div class="card-header pt-0">
										Extra Information
										<div class="card-tools">
										  <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
											<i class="fas fa-minus"></i>
										  </button>
										</div>
									  </div>
									<div class="card-body">
									</div>
								</div> -->
								<div class="card-footer" v-if="props.lead.lead_status != 3">
									<button type="submit" class="btn btn-info">
										<template v-if="!props.lead.lead_id" >Create</template >
										<template v-else>Update</template>
									</button>
								</div>
							</form>
						</div>
					</div>

					<div class="col-md-6" v-if="props.lead.lead_id">
						<div class="card">
							<div class="card-header">
								<span class="card-title">Follow Up</span>
							</div>
							<form @submit.prevent="handleCommentSubmit">
								<div class="card-body">
									<div class="form-group row" v-if="props.lead.lead_status != 3">
										<div class="input-group">
											<input type="text" class="form-control" v-model=" form.leadComment_comment " id="lead_comment" />
											<span class="input-group-append"> <button type="submit" class="btn btn-info btn-flat" > Update </button> </span>
										</div>
										<ul id="commentTemplate" class="mt-1">
											<li @click=" usePresetComment( 'Called no answer.', ) " class="btn btn-secondary btn-xs" > Called no answer. </li>
											<li @click=" usePresetComment( 'Customer will get back.', ) " class="btn btn-secondary btn-xs" > Customer will get back. </li>
										</ul>
									</div>
									<div class="row">
										<div class="direct-chat-messages">
											<div :class="commentAdminStyle(x.user.role.indexOf('Admin'), x.user_id)" v-for="x in props.lead?.comment">
												<div class="direct-chat-infos clearfix">
													<span class="text direct-chat-timestamp float-left">{{ TimeToString(x.created_at) }}</span>
													<span v-if="x.user.role.indexOf( 'Admin', ) >= 0 " class="text-danger ml-2" >Admin</span>
													<span v-if="x.user_id == -1" class="text-danger ml-2" >Bot</span>
													<Link v-if="(x.user.role.indexOf( 'Admin', ) < 0 || isMine( $page, x.user.id)) && x.user_id != -1" class="del_btn" method="delete" as="button" onclick="return confirm('Are you sure?')" :href=" route( 'leadComment.destroy', x.leadComment_id, ) " >
														<i class="fa fa-solid fa-trash" ></i>
													</Link>
												</div>
												<div>
													{{ x.leadComment_comment }}
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card-footer">
								<ul class="nav nav-pills ml-auto" style="float: right;">
									<li class="nav-item mr-2">
										<Link :href="route('quotation.create')" :data="{lead_id: props.lead.lead_id}" v-if="props.lead.lead_id && !props.lead.quotation_id">Create Quotation</Link>
										<a :href="route('quotation.edit', props.lead.quotation_id)" v-else>Quotation # {{ props.lead.quotation_id }}</a>
									</li>
									<li class="mr-2">&nbsp;|&nbsp;</li>
                  					<li class="nav-item">
                       					<Link v-if="props.lead.lead_status != 3" class="done_btn" method="post" as="button" onclick="return confirm('Are you sure?')" :href="route('lead.done', props.lead.lead_id)">Mark as Done</Link>
                            			<div v-else>Done ( <Link method="post" class="done_btn" as="button" onclick="return confirm('Are you sure?')" :href="route('lead.reopen', props.lead.lead_id)">Reopen</Link> )</div>
                            		</li>
                  <!-- <li class="nav-item dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Action <span class="caret"></span></a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" tabindex="-1" href="#">Action</a>
                      <a class="dropdown-item" tabindex="-1" href="#">Another action</a>
                      <a class="dropdown-item" tabindex="-1" href="#">Something else here</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" tabindex="-1" href="#">Separated link</a>
                    </div>
                  </li> -->
                </ul></div>
							</form>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="card" v-if="props.log?.length > 0">
							<div class="card-header">Activity Log</div>
							<div class="card-body table-responsive p-0">
								<table class="table table-hover text-nowrap">
									<thead>
										<tr>
											<th>Date/Time</th>
											<th>Log</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="x in props.log">
											<td class="col-md-2">{{ TimeToString(x.created_at) }}</td>
												<td v-html="tidyActivityLog(x)"></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					TODO:
						<ol>
							<li>salesperson may pick from unassigned pool</li>
							<li>salesperson may accept/reject lead</li>
							<!-- <li>reopen Done Status</li> -->
							<!-- <li>salesperson able to choose existing customer</li> -->
							<!-- <li>Customer extra information, full address, terms</li> -->
							<!-- <li>unable to edit/delete once it's marked as done</li> -->
							<!-- <li>move leadcomment and lead function in 1 service class</li> -->
							<!-- <li>lead comment should have web user that cannot be deleted by anyone</li> -->
						</ol>
				</div>
			</div>
		</section>

		<div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Default Modal</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              
				<div class="mb-3">
        <label for="customer_search">Search Customer</label>
        <input type="text" id="customer_search" v-model="form.customerQuery" class="form-control" placeholder="Type to search...">
		<input type="button" @click="searchCustomer" value="Search">
		{{ customerResult.length }}
        <ul v-for="x in customerResult" class="list-group mt-2">
          <li :data="x.customer_id" class="list-group-item" @click="selectCustomer(x)">
            {{ x.customer_name }} ({{ x.customer_phone }})
          </li>
        </ul>
      </div>
              <!-- <ul v-if="customerResults.length" class="list-group mt-2">
                <li v-for="customer in customerResults" :key="customer.id" class="list-group-item" @click="selectCustomer(customer)">
                  {{ customer.name }} ({{ customer.email }})
                </li>
              </ul> -->
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
	</AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, useForm, Head } from "@inertiajs/vue3";

const props = defineProps(["lead", "success", "meta", "users", "log"]);
const form = useForm({
	customerQuery: '',
	customer_id: props.lead?.customer_id ?? 0,
	lead_companyName: props.lead?.lead_companyName ?? "",
	lead_name: props.lead?.lead_name ?? "",
	lead_phone: props.lead?.lead_phone ?? "",
	lead_email: props.lead?.lead_email ?? "",
	lead_location: props.lead?.lead_location ?? "",
	user_id: props.lead?.user_id ?? 0,
	lead_source: props.lead?.lead_source ?? "",
	lead_enquiry: props.lead?.lead_enquiry ?? "",
	lead_remark: props.lead?.lead_remark ?? "",
	lead_premiseType: props.lead?.lead_premiseType ?? "",
	lead_serviceType: props.lead?.lead_service ?? "",
	leadComment_comment: props.lead?.lead_comment ?? "",
	lead_comment:
		props.lead?.comment?.map((x) => ({
			leadComment_username: x.user.name,
			leadComment_userrole: x.user.role,
			leadComment_id: x.leadComment_id,
			leadComment_comment: x.leadComment_comment,
		})) ?? [],
});

const disableInput = () => {
	if (props.lead?.lead_status == 3) {
		return "disableInput";
	}
};

const handleSubmit = () => {
	if (props.lead.lead_status == 3) { return; }
	if (props.lead.lead_id) {
		form.put(route("lead.update", props.lead.lead_id));
	} else {
		form.post(route("lead.store"));
	}
};

const customerResult = ref([]);
const searchCustomer = () => {
	customerResult.value = [];
	axios.get(route('customer.search'), {
		params: { query: form.customerQuery }
	}).then(response => {
		customerResult.value = response.data;
		// console.log(customerResult.value);
	}).catch(error => {
		console.error('There was an error fetching the customer:', error);
	});
};

const selectCustomer = (x) => {
	form.lead_name = x.customer_name;
	form.lead_phone = x.customer_phone;
	form.customer_id = x.customer_id;
	customerResult.value = [];

	$("#modal-default").modal("hide");
}

const handleCommentSubmit = () => {
	if (!props.lead.lead_id) {
		return;
	}
	form.post(route("lead.comment.store", props.lead.lead_id), {
		onSuccess: () => form.reset(),
	});
};

const usePresetComment = (val) => {
	if (form.leadComment_comment.length > 0) {
		val = form.leadComment_comment + " " + val;
	}
	form.leadComment_comment = val;
};

const halfOrFullWidth = () => {
	if (props.lead.lead_id) {
		return "col-md-6";
	}
	return "col-md-12";
};

const autoLabelWidthClass = () => {
	if (props.lead.lead_id) {
		return "col-form-label col-md-3";
	}
	return "col-form-label col-md-2";
};

const autoInputWidthClass = () => {
	if (props.lead.lead_id) {
		return "col-md-9";
	}
	return "col-md-10";
};

const commentAdminStyle = (x, y) => {
	var className = "direct-chat-msg";
	if (x >= 0 || y == -1) {
		className = "direct-chat-text font-italic";
	}
	return className;
};
</script>