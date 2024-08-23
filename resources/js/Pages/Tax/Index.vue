<template>
    <Head title="Tax List" />
 
    <AuthenticatedLayout>
        <template #header>{{ $t('Tax List') }}</template>

		<div class="card">
			<div class="card-header">
				<Link :href="route('tax.create')" class="btn btn-primary">{{ $t('New') }}</Link>
			</div>
			<div class="card-body">
				<table id="tables" class="table table-hover text-nowrap">
					<thead>
						<tr>
							<th>{{ $t('Name') }}</th>
							<th>{{ $t('Currency') }}</th>
							<th>{{ $t('Type') }}</th>
							<th>{{ $t('Amount') }}</th>
							<th>{{ $t('Actions') }}</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head } from '@inertiajs/vue3';
import DataTable from 'datatables.net-bs4';
import 'datatables.net-buttons-bs4';
import 'datatables.net-responsive-bs4';

export default {
    props: ['itemTemplate'],

    mixins: [],

	components: {
		Head,
        Link,
		AuthenticatedLayout
    },

    data () {
        return {
            errors: {},
        }
    },
    mounted() {
        this.initDataTables();

        window.thisPage = this;
    },
    methods: {
        initDataTables() {
            let t = this;

            t.table = new DataTable('#tables', {
                processing: true,
                serverSide: true,
                ajax: route('tax.datatables'),
                pagingType: 'simple',
                paging: false,
                order: [[2, 'desc']],
                stateSave: true,
                columnDefs: [
                    {
                        render: function (data, type, full, meta) {
                            return `
                                ${full.tax_type} - (${full.charge_type})
                            `;
                        },
                        targets: 2
                    },
                    {
                        render: function (data, type, full, meta) {
                            return `
                                <a href="${ route('tax.edit', { tax:data }) }" class="btn btn-warning">${ t.$t('Edit') }</a>
                                <a href="#" class="btn btn-danger" onclick="event.preventDefault(); window.thisPage.destroy(${data})">${ t.$t('Delete') }</a>
                            `;
                        },
                        targets: 4
                    }
                ],
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'currency', name: 'currency' },
                    { data: 'tax_type', name: 'tax_type' },
                    { data: 'amount', name: 'amount' },
                    { data: 'id', name: 'id' },
                ]
            });
        }, 
		destroy(id) {
			if (confirm(this.$t('Are you sure?'))) {
                let t = this;
                return axios.delete(route('tax.destroy', {tax: id}))
                        .then((response) => {
                            t.table.draw();
                        }).catch((e) => {
                            
                        });
			}
		}
    }
}
</script>