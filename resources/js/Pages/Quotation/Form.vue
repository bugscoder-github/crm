<script setup>
import { onMounted } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, useForm, Head } from "@inertiajs/vue3";

const emptyItem = [
    { quotationItem_id: 0, quotationItem_desc: '', quotationItem_ppu: 1, quotationItem_qty: 0, quotationItem_total: 0 },
];

const props = defineProps(['lead', 'quotation', 'quotation_items', 'success']);
let form = useForm({
	lead_id: props.lead?.lead_id ?? props.quotation?.lead_id ?? 0,
	quotation_company: props.lead?.lead_companyName ?? props.quotation?.quotation_company ?? '',
	quotation_premiseType: props.lead?.lead_premiseType ?? props.quotation?.quotation_premiseType ?? '',
	quotation_name: props.lead?.lead_name ?? props.quotation?.quotation_name ?? '',
	quotation_phone: props.lead?.lead_phone ?? props.quotation?.quotation_phone ?? '',
	quotation_email: props.lead?.lead_email ?? props.quotation?.quotation_email ?? '',
	quotation_deliveryAddress: props.lead?.lead_location ?? props.quotation?.quotation_deliveryAddress ?? '',
	quotation_billingAddress: props.quotation?.quotation_billingAddress ?? '',
	quotation_items: props.quotation_items?.length > 0 ? props.quotation_items?.map(x => ({
		quotationItem_id:    x.quotationItem_id,
		quotationItem_desc:  x.quotationItem_desc,
		quotationItem_ppu:   x.quotationItem_ppu,
		quotationItem_qty:   x.quotationItem_qty,
		quotationItem_total: x.quotationItem_total
	})) : emptyItem,
});

const handleSubmit = () => {
	if (props.quotation?.quotation_id) {
		form.put(route('quotation.update', props.quotation.quotation_id), {
			onSuccess: () => {
				updateQuotationItems();
			}
		});
	} else {
		form.post(route('quotation.store'), {
			onSuccess: () => {
				updateQuotationItems();
			}
		});
	};
};

const updateQuotationItems = () => {
	form.quotation_items = props.quotation_items?.length > 0 ? props.quotation_items?.map(x => ({
		quotationItem_id:    x.quotationItem_id,
		quotationItem_desc:  x.quotationItem_desc,
		quotationItem_ppu:   x.quotationItem_ppu,
		quotationItem_qty:   x.quotationItem_qty,
		quotationItem_total: x.quotationItem_total
	})) : emptyItem;
}

const addItem = () => {
    form.quotation_items.push(emptyItem[0]);
};

const removeItem = (index) => {
    form.quotation_items.splice(index, 1);
};
</script>
<template>
	<Head title="Members" />

	<AuthenticatedLayout>
		<template #header>New Quotation</template>

		<div class="alert alert-success alert-dismissible" v-if="props.success">
			{{props.success}}
			<template v-if="isAdmin($page)">
				[ <a :href="route('quotation.index')">Go to quotations list</a> ]
			</template>
		</div>

		<!-- {{ props.lead }} -->

		<section class="content">
			<div class="container-fluid">

				<div class="row">
					<form @submit.prevent="handleSubmit">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									Basic Information
									<div style="float: right">
										<!-- <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default" style="float: right;">Search Customer</button> -->
									</div>
									
								</div>

								<div class="card-body">
									<input type="hidden" id="lead_id" v-model="form.lead_id" v-if="form.lead_id">
									<div class="row">
										<div class="col-sm-8">
											<div class="form-group">
												<label for="quotation_company">Company</label>
												<input type="text" class="form-control" id="quotation_company" placeholder="Company Name" v-model="form.quotation_company">
												<span class="text-danger" v-if="form.errors.quotation_company" >{{ form.errors.quotation_company }}</span >
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<label for="quotation_premiseType">Premise Type</label>
												<input type="text" class="form-control" id="quotation_premiseType" placeholder="Premise Type" v-model="form.quotation_premiseType">
												<span class="text-danger" v-if="form.errors.quotation_premiseType" >{{ form.errors.quotation_premiseType }}</span >
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<label for="lead_name">Name</label>
												<input type="text" class="form-control" id="lead_name" placeholder="Name" v-model="form.quotation_name">
												<span class="text-danger" v-if="form.errors.quotation_name" >{{ form.errors.quotation_name }}</span >
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<label for="lead_phone">Phone</label>
												<input type="text" class="form-control" id="lead_phone" placeholder="Phone" v-model="form.quotation_phone">
												<span class="text-danger" v-if="form.errors.quotation_phone" >{{ form.errors.quotation_phone }}</span >
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<label for="exampleInputEmail1">Email</label>
												<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email" v-model="form.quotation_email">
												<span class="text-danger" v-if="form.errors.quotation_email" >{{ form.errors.quotation_email }}</span >
											</div>
										</div>
									</div>
									<hr>
									<div class="form-group row">
										<label for="lead_companyName" class="col-sm-2 col-form-label">Delivery Address</label >
										<div class="col-sm-10">
											<input type="text" class="form-control" id="lead_companyName" placeholder="Delivery Address" v-model="form.quotation_deliveryAddress" />
											<span class="text-danger" v-if="form.errors.quotation_deliveryAddress" >{{ form.errors.quotation_deliveryAddress }}</span >
										</div>
									</div>
									<div class="form-group row">
										<label for="lead_companyName" class="col-sm-2 col-form-label">Billing Address</label >
										<div class="col-sm-10">
											<input type="text" class="form-control" id="lead_companyName" placeholder="Billing Address" v-model="form.quotation_billingAddress" />
											<span class="text-danger" v-if="form.errors.quotation_billingAddress" >{{ form.errors.quotation_billingAddress }}</span >
										</div>
									</div>
									<div class="form-group row">
										<div class="offset-sm-2 col-sm-10">
											<div class="form-check">
												<input type="checkbox" class="form-check-input" id="exampleCheck2">
												<label class="form-check-label" for="exampleCheck2">Same as Delivery Address</label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									Basic Information
									<div style="float: right">
										<!-- <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default" style="float: right;">Search Customer</button> -->
									</div>
									
								</div>
							</div><br>


					<label class="col-md-3">Item Name</label>
                <label class="col-md-3">PPU</label>
                <label class="col-md-3">Quantity</label>
                <label class="col-md-3">Price</label>
					<div v-for="(item, index) in form.quotation_items" :key="index">
						<input type="hidden" v-model="item.quotationItem_id">
						<input type="text"   class="col-md-3" v-model="item.quotationItem_desc">
						<input type="number" class="col-md-3" v-model="item.quotationItem_qty">
						<input type="number" class="col-md-3" v-model="item.quotationItem_ppu">
						<input type="number" class="col-md-3" v-model="item.quotationItem_total">
						<button @click.prevent="removeItem(index)">Remove</button>
					</div>
					<button @click.prevent="addItem">Add Item</button><br>
				</div>
					
					<button type="submit" class="btn btn-info">
						Create
					</button>
				</form>
			</div>
		</div>
		</section>
	</AuthenticatedLayout>
</template>