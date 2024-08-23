<template>
    <Head title="Service List" />
 
    <AuthenticatedLayout>
        <template #header>{{ $t('Service List') }}</template>

		<div class="card">
			<div class="card-header">
				<Link :href="route('service.create')" class="btn btn-primary">{{ $t('New') }}</Link>
			</div>
			<div class="card-body">
				<table id="tables" class="table table-hover text-nowrap">
					<thead>
						<tr>
							<th>{{ $t('Name') }}</th>
							<th>{{ $t('Price') }}</th>
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
                ajax: route('services.datatables'),
                pagingType: 'simple',
                paging: false,
                order: [[2, 'desc']],
                stateSave: true,
                columnDefs: [
                    {
                        render: function (data, type, full, meta) {
                            return `
                                <a href="#" class="btn btn-danger" onclick="event.preventDefault(); window.thisPage.destroy(${data})">${ t.$t('Delete') }</a>
                            `;
                        },
                        targets: 2
                    }
                ],
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'price', name: 'price' },
                    { data: 'id', name: 'id' },
                ]
            });
        }, 
		destroy(id) {
			if (confirm(this.$t('Are you sure?'))) {
                let t = this;
                return axios.delete(route('service.destroy', {service: id}))
                        .then((response) => {
                            t.table.draw();
                        }).catch((e) => {
                            
                        });
			}
		}
    }
}
</script>