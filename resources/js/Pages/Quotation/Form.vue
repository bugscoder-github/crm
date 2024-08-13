<template>
	<Head title="Members" />

	<AuthenticatedLayout>
		<template #header>New Quotation</template>

		<div class="alert alert-success alert-dismissible" v-if="success">
			{{success}}
			<template v-if="isAdmin($page)">
				[ <a :href="route('quotation.index')">Go to quotations list</a> ]
			</template>
		</div>

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
							</div>
							<div class="card">
								<div class="card-header">
									Items
									<div style="float: right">
										<!-- <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default" style="float: right;">Search Customer</button> -->
									</div>
								</div>

								<div class="card-body">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th></th>
												<th>{{ $t('Service') }}</th>
												<th>{{ $t('Quantity') }}</th>
												<th>{{ $t('Unit Amount') }}</th>
												<th>{{ $t('Sub Total') }}</th>
												<th>{{ $t('Total Discount') }}</th>
												<th>{{ $t('Line Amount') }}</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(item, key) in form.items">
												<td>
													<input class="form-check-input mx-auto" type="checkbox" v-model="item.is_enabled" @change="estimate">
												</td>
												<td>
													<div class="form-floating">
														<input :id="'name' + key" type="text" class="form-control border-bottom-0 rounded-0 rounded-top" :placeholder="$t('Service')" v-model="item.name" :class="{ 'is-invalid': getError('item.name') }">
														<small class="text-danger" v-if="getError('item.name')">{{ getError('item.name') }}</small>
													</div>

													<div class="form-floating">
														<textarea :id="'description' + key" type="text" class="form-control rounded-0 rounded-bottom" :placeholder="$t('Description')" v-model="item.description" :class="{ 'is-invalid': getError('item.description') }"></textarea>
														<small class="text-danger" v-if="getError('item.description')">{{ getError('item.description') }}</small>
													</div>

													<div v-if="item.discounts && item.discounts.length > 0">
														<hr class="my-2">
														<div class="card">
															<div class="card-body pb-0">
																<ul>
																	<li v-for="discount in item.discounts">
																		{{ discount.name }} (
																			<span v-if="discount.discount_type === 'percentage'">{{ discount.amount }}%</span>
																			<span v-else>{{ form.data.currency }} {{ discount.amount }}</span>
																		)<br>
																		<small class="text-muted">{{ discount.description }}</small>
																	</li>
																</ul>
															</div>
														</div>
													</div>
												</td>
												<td>
													<div class="form-floating mb-3">
														<input :id="'quantity' + key" type="number" class="form-control" :placeholder="$t('Quantity')" v-model="item.quantity" :class="{ 'is-invalid': getError(`items.${key}.quantity`) }" @change="estimate">
														<small class="text-danger" v-if="getError(`items.${key}.quantity`)">{{ getError(`items.${key}.quantity`) }}</small>
													</div>
												</td>
												<td>
													<div class="form-floating mb-3" v-if="item.item_type === 'custom'">
														<input :id="'price' + key" type="number" class="form-control" :placeholder="$t('Unit Amount')" v-model="item.unit_amount" :class="{ 'is-invalid': getError('item.unit_amount') }" @change="estimate">
														<small class="text-danger" v-if="getError('item.unit_amount')">{{ getError('item.unit_amount') }}</small>
													</div>
													<div class="form-floating mb-3" v-else>
														{{ item.unit_amount }}
													</div>
												</td>
												<td>{{ item.sub_total }}</td>
												<td>({{ item.discount_amount }})</td>
												<td>{{ item.line_amount }}</td>
												<td>
													<a class="btn btn-danger" href="#" @click.prevent="deleteItem(key)">{{ $t('Delete') }}</a>
												</td>
											</tr>
											<tr>
												<td class="text-end" colspan="4">{{ $t('Sub Total') }}</td>
												<td colspan="4">{{ form.sub_total }}</td>
											</tr>
											<tr>
												<td class="text-end" colspan="4">{{ $t('Total Discount') }}</td>
												<td colspan="4">{{ form.total_discount }}</td>
											</tr>
											<tr>
												<td class="text-end"colspan="4">
													<span v-if="form.tax_name && form.tax_charge_type">{{ form.tax_name }} <span v-if="form.tax_charge_type === 'percentage'">({{ form.tax_rate }}%)</span></span>
													<span v-else>{{ $t('Tax Rate') }}</span>
												</td>
												<td colspan="4">
													{{ form.total_tax }} <span v-if="form.tax_type === 'inclusive'">({{ $t('Inclusive') }})</span><span v-else>({{ $t('Exclusive') }})</span>
												</td>
											</tr>
											<tr>
												<td class="text-end"colspan="4">{{ $t('Total') }}</td>
												<td colspan="4">{{ form.total_amount }}</td>
											</tr>
										</tbody>
									</table>
									<div class="ms-1 mt-2 row form-group">
										<a href="#" class="btn btn-primary me-2" @click="addItem('single-item')">Add Item</a>
										<a href="#" class="btn btn-outline-primary me-2" @click="addItem('template-item')">Add Template Item</a>
										<a href="#" class="btn btn-secondary" @click="addItem('custom-item')">Add Custom Item</a>
									</div>
								</div>
							</div>
							
							<button type="submit" class="btn btn-info" @click="save()">Create</button>&nbsp;
							<a :href="route('quotation.pdf', form.id)" v-if="form.id">PDF</a>
						</form>
					</div>
				</div>
			</div>
		</section>

		<ItemModal 
			@modalAddItem="modalAddItem" 
			ref="itemModal" 
			:item-template="itemTemplate">
		</ItemModal>
		<ItemTemplateModal 
			@modalTemplateAddItem="modalTemplateAddItem" 
			ref="itemTemplateModal" 
			:item-template="itemTemplate">
		</ItemTemplateModal>
	</AuthenticatedLayout>
</template>
<script>
import { onMounted, ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import FormValidation from "@/Helpers/FormValidation.vue";
import ItemModal from "./ItemModal.vue";
import ItemTemplateModal from "./ItemTemplateModal.vue";
import { useForm, Head } from "@inertiajs/vue3";

export default {
    props: [
		'lead',
		'itemTemplate',
		'form', 
		'success'
    ],

    mixins: [FormValidation],

    components: {
		Head,
		ItemModal,
		ItemTemplateModal,
		AuthenticatedLayout
    },

    data () {
        return {
           errors: {}
        }
    },
    mounted() {
        this.estimate();
    },
    methods: {
        save() {
            let t = this;
            t.errors = {};

			if (this.form.id) {
				return axios.put(route('quotation.update', { id: t.form.id }), this.form)
					.then((response) => {
					}).catch((e) => {
					
					});
			} else {
				return axios.post(route('quotation.store'), this.form)
					.then((response) => {
					}).catch((e) => {
					
					});
			};
        },
        deleteItem(key) {
            this.form.items.splice(key, 1);
            this.estimate();
        },
		addItem(method) {
			let template = {...this.itemTemplate};
			
			switch (method) {
				case 'single-item':
					this.$refs.itemModal.open();
					break;
				case 'template-item':
					this.$refs.itemTemplateModal.open();
					break;
				case 'custom-item':
					template.item_type = 'custom';
					this.form.items.push(template);
					break;
			}
		},
		modalTemplateAddItem(arrayTemplate) {
			this.form.items = this.form.items.concat(arrayTemplate);
			this.estimate();
		},
		modalAddItem(template) {
			this.form.items.push(template);
			this.estimate();
		},
        estimate() {
            let t = this;
            t.errors = {};
            if (t.form.items.length > 0) {
                return axios.post(route('shared.estimate'), t.form).then((response) => {
                    let data = response.data;

                    Object.assign(t.form, data);
                }).catch((e) => {
                    if (e.response.status === 404) {
                        Swal.fire({
                            title: e.response.data.message,
                            backdrop: true,
                            allowOutsideClick: true,
                        });
                    } else {
                        t.errors = e.response.data['errors'];
                    }
                });
            }
        }
    }
}

</script>