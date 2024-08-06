<script setup>
import { onMounted, ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useForm, Head } from "@inertiajs/vue3";

const emptyItem = [
    { invoiceItem_id: 0, invoiceItem_desc: '', invoiceItem_ppu: 1, invoiceItem_qty: 0, invoiceItem_total: 0 },
];

const props = defineProps(['invoice', 'invoice_items', 'quotation', 'quotation_items', 'success']);
let form = useForm({
	invoice_sstPct: props.invoice?.invoice_sstPct ?? 8,
	prodServiceQuery: '',
	quotation_id: props.quotation?.quotation_id ?? 0,
	invoice_company: props.quotation?.quotation_companyName ?? props.invoice?.invoice_company ?? '',
	invoice_premiseType: props.quotation?.quotation_premiseType ?? props.invoice?.invoice_premiseType ?? '',
	invoice_name: props.quotation?.quotation_name ?? props.invoice?.invoice_name ?? '',
	invoice_phone: props.quotation?.quotation_phone ?? props.invoice?.invoice_phone ?? '',
	invoice_email: props.quotation?.quotation_email ?? props.invoice?.invoice_email ?? '',
	invoice_deliveryAddress: props.quotation?.quotation_location ?? props.invoice?.invoice_deliveryAddress ?? '',
	invoice_billingAddress: props.quotation?.quotation_billingAddress ?? '',
	invoice_tnc: props.quotation?.quotation_tnc ?? props.invoice?.invoice_tnc ?? '',
	invoice_remark: props.quotation?.quotation_remark ?? props.invoice?.invoice_remark ?? '',
	invoice_items: props.quotation_items?.length > 0 ? props.quotation_items?.map(x => ({
		invoiceItem_id:    x.quotationItem_id,
		invoiceItem_desc:  x.quotationItem_desc,
		invoiceItem_ppu:   x.quotationItem_ppu,
		invoiceItem_qty:   x.quotationItem_qty,
		invoiceItem_total: x.quotationItem_total
	})) : props.invoice_items?.length > 0 ? props.invoice_items?.map(x => ({
		invoiceItem_id:    x.invoiceItem_id,
		invoiceItem_desc:  x.invoiceItem_desc,
		invoiceItem_ppu:   x.invoiceItem_ppu,
		invoiceItem_qty:   x.invoiceItem_qty,
		invoiceItem_total: x.invoiceItem_total
	})) : emptyItem,
});

const handleSubmit = () => {
	if (props.invoice?.invoice_id) {
		form.put(route('invoice.update', props.invoice.invoice_id), {
			onSuccess: () => {
				updateQuotationItems();
			}
		});
	} else {
		form.post(route('invoice.store'), {
			onSuccess: () => {
				updateQuotationItems();
			}
		});
	};
};

const updateQuotationItems = () => {
	form.invoice_items = props.invoice_items?.length > 0 ? props.invoice_items?.map(x => ({
		invoiceItem_id:    x.invoiceItem_id,
		invoiceItem_desc:  x.invoiceItem_desc,
		invoiceItem_ppu:   x.invoiceItem_ppu,
		invoiceItem_qty:   x.invoiceItem_qty,
		invoiceItem_total: x.invoiceItem_total
	})) : emptyItem;
}

const addItem = () => {
    form.invoice_items.push(emptyItem[0]);
};

const removeItem = (index) => {
    form.invoice_items.splice(index, 1);
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

let ppuTotal = 0, qtyTotal = 0, subTotal = 0, sst = 0;
const grandTotal = ref(0);
const calcTotal = () => {
	ppuTotal = 0; qtyTotal = 0; subTotal = 0; sst = 0;

	for (let x in form.invoice_items) {
		let thisTotal = form.invoice_items[x].invoiceItem_ppu;
		let thisQty   = form.invoice_items[x].invoiceItem_qty;
		ppuTotal   += thisTotal;
		qtyTotal   += thisQty;
		subTotal   += (thisTotal * thisQty);

		form.invoice_items[x].invoiceItem_total = (thisTotal * thisQty);
	}

	sst = subTotal*(form.invoice_sstPct/100);
	sst = parseFloat(sst.toFixed(2));
	grandTotal.value = subTotal + sst;
}
calcTotal();

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

			form.invoice_items.push({
				invoiceItem_id: 0,
				prodService_id: prodServiceResult.value[data].productService_id,
				invoiceItem_desc: prodServiceResult.value[data].productService_desc,
				invoiceItem_ppu: prodServiceResult.value[data].productService_ppu,
				invoiceItem_qty: 1,
				invoiceItem_total: prodServiceResult.value[data].productService_ppu
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

	$(".reCalc").keyup(calcTotal);

	textareaHeightAuto();
});
</script>
<template>
	<Head title="Members" />

	<AuthenticatedLayout>
		<template #header>New Invoice</template>

		<div class="alert alert-success alert-dismissible" v-if="props.success">
			{{props.success}}
			<template v-if="isAdmin($page)">
				[ <a :href="route('invoice.index')">Go to invoices list</a> ]
			</template>
		</div>

		<!-- {{ props.quotation_items }} -->

		<section class="content">
			<div class="container-fluid">

				<div class="row">
						<div class="col-md-12">


							<div class="callout callout-info" v-if="form.lead_id">
								Created via <a :href="route('lead.edit', form.lead_id)" target="_blank">Lead #{{ form.lead_id }}</a>
							</div>
							<form @submit.prevent="handleSubmit">
							<div class="card">
								<div class="card-header">
									Basic Information
									<div style="float: right">
										<!-- <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default" style="float: right;">Search Customer</button> -->
									</div>
									
								</div>

								<div class="card-body">
									<input type="hidden" id="invoice_id" v-model="form.invoice_id" v-if="form.invoice_id">
									<div class="row">
										<div class="col-sm-8">
											<div class="form-group">
												<label for="invoice_company">Company</label>
												<input type="text" class="form-control" id="invoice_company" placeholder="Company Name" v-model="form.invoice_company">
												<span class="text-danger" v-if="form.errors.invoice_company" >{{ form.errors.invoice_company }}</span >
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<label for="invoice_premiseType">Premise Type</label>
												<input type="text" class="form-control" id="invoice_premiseType" placeholder="Premise Type" v-model="form.invoice_premiseType">
												<span class="text-danger" v-if="form.errors.invoice_premiseType" >{{ form.errors.invoice_premiseType }}</span >
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label for="lead_name">Name</label>
												<input type="text" class="form-control" id="lead_name" placeholder="Name" v-model="form.invoice_name">
												<span class="text-danger" v-if="form.errors.invoice_name" >{{ form.errors.invoice_name }}</span >
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label for="lead_phone">Phone</label>
												<input type="text" class="form-control" id="lead_phone" placeholder="Phone" v-model="form.invoice_phone">
												<span class="text-danger" v-if="form.errors.invoice_phone" >{{ form.errors.invoice_phone }}</span >
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<label for="exampleInputEmail1">Email</label>
												<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email" v-model="form.invoice_email">
												<span class="text-danger" v-if="form.errors.invoice_email" >{{ form.errors.invoice_email }}</span >
											</div>
										</div>
									</div>
									<hr>
									<div class="form-group row">
										<label for="lead_companyName" class="col-sm-2 col-form-label">Delivery Address</label >
										<div class="col-sm-10">
											<input type="text" class="form-control" id="lead_companyName" placeholder="Delivery Address" v-model="form.invoice_deliveryAddress" />
											<span class="text-danger" v-if="form.errors.invoice_deliveryAddress" >{{ form.errors.invoice_deliveryAddress }}</span >
										</div>
									</div>
									<div class="form-group row">
										<label for="lead_companyName" class="col-sm-2 col-form-label">Billing Address</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="lead_companyName" placeholder="Billing Address" v-model="form.invoice_billingAddress" />
											<span class="text-danger" v-if="form.errors.invoice_billingAddress" >{{ form.errors.invoice_billingAddress }}</span >
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
										<div class="col-1"></div>
										<div class="col-7">Item Name</div>
										<div class="col-2 text-center">Per Service</div>
										<div class="col-1 text-center">Frequency</div>
										<div class="col-1 text-center">Amount</div>
									</div>
									<div class="row form-group" v-for="(item, index) in form.invoice_items" :key="index">
										<input type="hidden" v-model="item.invoiceItem_id">
										<input type="hidden" class="prodService_id" :data="item.prodService_id">
										<div class="col-1"><button @click.prevent="removeItem(index)" class="btn btn-sm bg-danger"><i class="fas fa-times"></i></button></div>
										<div class="col-7">
											<textarea v-model="item.invoiceItem_desc" class="form-control"></textarea>
										</div>
										<div class="col-2">
											<input type="number" class="reCalc form-control text-right" v-model="item.invoiceItem_ppu">
										</div>
										<div class="col-1">
											<input type="number" class="reCalc form-control text-right" v-model="item.invoiceItem_qty">
										</div>
										<div class="col-1 col-form-label text-right">{{ item.invoiceItem_total }}</div>
									</div>
									<div class="row form-group">
										<div class="col-1"></div>
										<div class="col-7"></div>
										<div class="col-2 text-right">{{ ppuTotal }}</div>
										<div class="col-1 text-right">{{ qtyTotal }}</div>
										<div class="col-1 text-right">{{  subTotal }}</div>
									</div>
									<div class="odd row form-group">
										<div class="col-1"></div>
										<div class="col-7"></div>
										<div class="col-3 text-right p-0">Subtotal (RM)</div>
										<div class="col-1 text-right p-0">{{  subTotal }}</div>
									</div>
									<div class="odd row form-group">
										<div class="col-1"></div>
										<div class="col-7"></div>
										<div class="col-3 text-right p-0">
											<input type="number" class="reCalc text-right col-2" v-model="form.invoice_sstPct"> % SST
										</div>
										<div class="col-1 text-right p-0">{{  sst }}</div>
									</div>
									<div class="odd row form-group">
										<div class="col-1"></div>
										<div class="col-7"></div>
										<div class="col-3 text-right p-0">Total Amount (RM)</div>
										<div class="col-1 text-right p-0">{{ grandTotal }}</div>
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
												<label for="invoice_remark">Remark</label>
												 <textarea v-model="form.invoice_remark" class="form-control"></textarea>
												<span class="text-danger" v-if="form.errors.invoice_remark" >{{ form.errors.invoice_remark }}</span >
											</div>
										</div>
										<div class="col-sm-12">
											<div class="form-group">
												<label for="invoice_tnc">Terms and Condition</label>
												<textarea v-model="form.invoice_tnc" class="form-control"></textarea>
												<span class="text-danger" v-if="form.errors.invoice_tnc" >{{ form.errors.invoice_tnc }}</span >
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<button type="submit" class="btn btn-info">Create</button>&nbsp;
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