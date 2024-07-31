<script setup>
import { onMounted, ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useForm, Head } from "@inertiajs/vue3";

const emptyItem = [
    { quotationItem_id: 0, quotationItem_desc: '', quotationItem_ppu: 1, quotationItem_qty: 0, quotationItem_total: 0 },
];

const props = defineProps(['lead', 'quotation', 'quotation_items', 'success']);
let form = useForm({
	prodServiceQuery: '',
	lead_id: props.lead?.lead_id ?? props.quotation?.lead_id ?? 0,
	quotation_company: props.lead?.lead_companyName ?? props.quotation?.quotation_company ?? '',
	quotation_premiseType: props.lead?.lead_premiseType ?? props.quotation?.quotation_premiseType ?? '',
	quotation_name: props.lead?.lead_name ?? props.quotation?.quotation_name ?? '',
	quotation_phone: props.lead?.lead_phone ?? props.quotation?.quotation_phone ?? '',
	quotation_email: props.lead?.lead_email ?? props.quotation?.quotation_email ?? '',
	quotation_deliveryAddress: props.lead?.lead_location ?? props.quotation?.quotation_deliveryAddress ?? '',
	quotation_billingAddress: props.quotation?.quotation_billingAddress ?? '',
	quotation_tnc: props.quotation?.quotation_tnc ?? '',
	quotation_remark: props.quotation?.quotation_remark ?? '',
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

let ppuTotal = 0, qtyTotal = 0, subTotal = 0, sst = 0, grandTotal = 0;
const calcTotal = () => {
	ppuTotal = 0; qtyTotal = 0; subTotal = 0; sst = 0; grandTotal = 0;

	for (let x in form.quotation_items) {
		let thisTotal = form.quotation_items[x].quotationItem_ppu;
		let thisQty   = form.quotation_items[x].quotationItem_qty;
		ppuTotal   += thisTotal;
		qtyTotal   += thisQty;
		subTotal   += (thisTotal * thisQty);
	}

	sst = subTotal*(0.8);
	grandTotal = subTotal + sst;
}
calcTotal();

const addItem = () => {
    form.quotation_items.push(emptyItem[0]);
};

const removeItem = (index) => {
    form.quotation_items.splice(index, 1);
};

const prodServiceResult = ref([]);
const searchProdService = () => {
	prodServiceResult.value = [];
	axios.get(route('prodService.search'), {
		params: { query: form.prodServiceQuery }
	}).then(response => {
		prodServiceResult.value = response.data;
		// console.log(prodServiceResult.value);
	}).catch(error => {
		console.error('There was an error fetching the prodService:', error);
	});
};

const textareaHeightAuto = () => {
	$("textarea").each(function() {
		$(this).height($(this).prop("scrollHeight"));
	});
};

const selectProdService = () => {
	$(".prodService_ind").each(function() {
		var $this = $(this);
		if ($this.prop('checked')) {
			let data = $this.attr('data');

			form.quotation_items.push({
				quotationItem_id: 0,
				prodService_id: prodServiceResult.value[data].productService_id,
				quotationItem_desc: prodServiceResult.value[data].productService_desc,
				quotationItem_ppu: prodServiceResult.value[data].productService_ppu,
				quotationItem_qty: 1,
				quotationItem_total: prodServiceResult.value[data].productService_ppu
			});
		}
	});

	prodServiceResult.value = [];
	$("#modal-default").modal("hide");
	$('#modal-default').on('hidden.bs.modal', function (e) {	
		textareaHeightAuto();
		calcTotal();
	});
	// $("textarea").height($(this).prop(scrollHeight));
	// resizeTextArea($("textarea"));
};

const disabledExist = (x) => {
	if ($(".prodService_id[data='"+x+"']").length > 0) {
		return ' disabled';
	}
};

onMounted(() => {
	$('#modal-default').on('show.bs.modal', function (e) {	
		searchProdService();
	});

	textareaHeightAuto();
});
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
						<div class="col-md-12">
							<form @submit.prevent="handleSubmit">
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
										<div class="col-sm-6">
											<div class="form-group">
												<label for="lead_name">Name</label>
												<input type="text" class="form-control" id="lead_name" placeholder="Name" v-model="form.quotation_name">
												<span class="text-danger" v-if="form.errors.quotation_name" >{{ form.errors.quotation_name }}</span >
											</div>
										</div>
										<div class="col-sm-2">
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
										<label for="lead_companyName" class="col-sm-2 col-form-label">Billing Address</label>
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
									Items
									<div style="float: right">
										<!-- <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default" style="float: right;">Search Customer</button> -->
									</div>
									
								</div>


								<div class="card-body">
									<div class="row form-group">
										<div class="col-7">Item Name</div>
										<div class="col-2 text-center">Per Service</div>
										<div class="col-1 text-center">Frequency</div>
										<div class="col-1 text-center">Amount</div>
										<div class="col-1"></div>
									</div>
									<div class="row form-group" v-for="(item, index) in form.quotation_items" :key="index">
										<input type="hidden" v-model="item.quotationItem_id">
										<input type="hidden" class="prodService_id" :data="item.prodService_id">
										<div class="col-7">
											<textarea v-model="item.quotationItem_desc" class="form-control"></textarea>
										</div>
										<div class="col-2">
											<input type="number" class="form-control text-right" v-model="item.quotationItem_ppu">
										</div>
										<div class="col-1">
											<input type="number" class="form-control text-right" v-model="item.quotationItem_qty">
										</div>
										<div class="col-1 col-form-label text-right">{{ item.quotationItem_total }}</div>
										<div class="col-1"><button @click.prevent="removeItem(index)" class="btn btn-sm bg-danger"><i class="fas fa-times"></i></button></div>
										<!-- <div class="col-4">
											<div class="row form-group">
												<div class="col-9">
													<input type="number" class="form-control" v-model="item.quotationItem_ppu">
												</div>
												<div class="col-3">
													<input type="number" class="form-control" v-model="item.quotationItem_qty">
												</div>
											</div>
											<div class="row">
												<div class="col-4">
													Total: {{ item.quotationItem_total }}
												</div>
												<div class="col-1"><button @click.prevent="removeItem(index)">Remove</button></div>
											</div>
										</div> -->
									</div>
									<div class="row form-group">
										<div class="col-7"></div>
										<div class="col-2 text-right">{{ ppuTotal }}</div>
										<div class="col-1 text-right">{{ qtyTotal }}</div>
										<div class="col-1 text-right">{{  subTotal }}</div>
										<div class="col-1"></div>
									</div>
									<div class="odd row form-group">
										<div class="col-7"></div>
										<div class="col-3 text-right p-0">Subtotal (RM)</div>
										<div class="col-1 text-right p-0">{{  subTotal }}</div>
										<div class="col-1"></div>
									</div>
									<div class="odd row form-group">
										<div class="col-7"></div>
										<div class="col-3 text-right p-0">8% SST</div>
										<div class="col-1 text-right p-0">{{  sst }}</div>
										<div class="col-1"></div>
									</div>
									<div class="odd row form-group">
										<div class="col-7"></div>
										<div class="col-3 text-right p-0">Total Amount (RM)</div>
										<div class="col-1 text-right p-0">{{  grandTotal }}</div>
										<div class="col-1"></div>
									</div>
									<div class="row form-group">
										<button @click.prevent="addItem">Add Item</button><br>
										<div data-toggle="modal" data-target="#modal-default" style="cursor: pointer;">Search Item</div>
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

								<div class="card-body">
									<div class="row">
										<div class="col-sm-12">
											<div class="form-group">
												<label for="quotation_remark">Remark</label>
												 <textarea v-model="form.quotation_remark" class="form-control"></textarea>
												<span class="text-danger" v-if="form.errors.quotation_remark" >{{ form.errors.quotation_remark }}</span >
											</div>
										</div>
										<div class="col-sm-12">
											<div class="form-group">
												<label for="quotation_tnc">Terms and Condition</label>
												<textarea v-model="form.quotation_tnc" class="form-control"></textarea>
												<span class="text-danger" v-if="form.errors.quotation_tnc" >{{ form.errors.quotation_tnc }}</span >
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<button type="submit" class="btn btn-info">
								Create
							</button>
							<div class="card"></div>
					</form>
				</div>
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
        <label for="prodServiceSearch">Search Product/Service</label>
        <input type="text" id="prodServiceSearch" v-model="form.prodServiceQuery" class="form-control" placeholder="Type to search...">
		<input type="button" @click="searchProdService" value="Search">
		{{ prodServiceResult.length }}
        <ul class="list-group mt-2">
          <li v-for="(x, y) in prodServiceResult" :data="y" class="list-group-item">
            <input type="checkbox" class="prodService_ind" :data="y" :disabled="disabledExist(x.productService_id)">{{ x.productService_desc }}
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
              <button type="button" class="btn btn-primary" @click="selectProdService()">Select</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
	</AuthenticatedLayout>
</template>