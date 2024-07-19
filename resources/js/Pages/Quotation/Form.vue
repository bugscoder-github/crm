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
	quotation_name: props.lead?.lead_name ?? props.quotation?.quotation_name ?? '',
	quotation_phone: props.lead?.lead_phone ?? props.quotation?.quotation_phone ?? '',
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

		<section class="content">
			<div class="container-fluid">
				<form @submit.prevent="handleSubmit">
					<input type="hidden" id="lead_id" v-model="form.lead_id" v-if="form.lead_id"><br>
					<input id="lead_name" v-model="form.quotation_name"><br>
					<input id="lead_phone" v-model="form.quotation_phone"><br>

				<div>

					{{ form.quotation_items }}


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
		</section>
	</AuthenticatedLayout>
</template>